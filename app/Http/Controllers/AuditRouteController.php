<?php

namespace App\Http\Controllers;

use Auth;
use PDF;
use File;
use Mail;
use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Mail\SendReport;

use App\Models\AuditRoute;
use App\Models\AuditRecord;
use App\Models\AuditRouteStoreImg;
use App\Models\MainAttribute;
use App\Models\AuditRecordFail;
use App\Models\AuditRecordItem;
use App\Models\PendingCheck;
use App\Models\PendingCheckLog;
use App\Models\Counseling;

use App\Http\Controllers\Contracts\InterfaceController;

class AuditRouteController extends BasicController {

    public function index(Request $request) {

        $filters = $this->getFilters($request);

        if ($request->user()->super_admin != 1 && $request->user()->role->has_audit_route != 1) {
            if ($request->user()->cannot('browse_'.$this->slug,  $this->model)) {
                return view('alerts.error', [
                    'msg' => '您的權限不足, 請洽管理人員開通權限',
                    'redirectURL' => '/'
                ]);
            }

            $filters['main_user_id'] = $request->user()->id;
            $filters['sub_user_id'] = $request->user()->id;
        }

        $list = $this->model->getListByFilters($this->menu->menuDetails, $filters);

        if(view()->exists($this->slug.'.index')) {
            $this->indexView = $this->slug.'.index';
        }

        return view($this->indexView, [
            'filters' => $filters,
            'list' => $list
        ]);
    } 

    public function store(Request $request) {
        if ($request->user()->super_admin != 1) {
            if ($request->user()->cannot('create_'.$this->slug,  $this->model)) {
                return view('alerts.error', [
                    'msg' => '您的權限不足, 請洽管理人員開通權限',
                    'redirectURL' => '/'
                ]);
            }
        }

        $validator = $this->createRule($request);

        if (!is_array($validator) && $validator->fails()){
            return view('alerts.error',[
                'msg'=>$validator->errors()->all()[0],
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }
        
        DB::beginTransaction();

        try {
            $formData = $request->except('_token');
            $formData['id'] = uniqid();
            $formData['system_type_id'] = Auth::user()->role->system_type_id;

            if ($this->menu->menuDetails->count() > 0) {
                foreach ($this->menu->menuDetails as $detail) {
                    if (isset($formData[$detail->field])) {
                        if ($detail->type == 'image' || $detail->type == 'file') {
                            if (is_object($formData[$detail->field]) && $formData[$detail->field]->getSize() > 0) {
                                $formData[$detail->field] = $this->storeFile($formData[$detail->field], $this->slug);
                            }
                        }
                    }
                }

                $log = $this->model->getLastStatusByStoreId($formData['store_id']);

                if ($log) {
                    if ($log->audit_status == 1) {
                        $formData['audit_status'] = 2;
                    }
                }

                $audit = $this->model->create($formData);
                $this->createAuditRecords($audit, $log);

                DB::commit();
            
                return view('alerts.success', [
                    'msg'=>'資料新增成功',
                    'redirectURL'=>route($this->slug.'.index')
                ]);
            }

            DB::rollBack();

            return view('alerts.error', [
                'msg'=>'資料新增失敗, 無該功能項之細項設定',
                'redirectURL'=>route($this->slug.'.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return view('alerts.error',[
                'msg'=>$e->getMessage(),
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }
    }

    public function update(Request $request, $id) {
        if ($request->user()->super_admin != 1) {
            if ($request->user()->cannot('update_'.$this->slug,  $this->model)) {
                return view('alerts.error', [
                    'msg' => '您的權限不足, 請洽管理人員開通權限',
                    'redirectURL' => '/'
                ]);
            }
        }
        $validator = $this->updateRule($request);

        if (!is_array($validator) && $validator->fails()){
            return view('alerts.error',[
                'msg'=>$validator->errors()->all()[0],
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }

        $data = $this->model->find($id);

        DB::beginTransaction();

        try {
            $formData = $request->except('_token', '_method');

            if ($this->menu->menuDetails->count() > 0) {
                foreach ($this->menu->menuDetails as $detail) {
                    if (isset($formData[$detail->field])) {
                        if ($detail->type == 'image' || $detail->type == 'file') {
                            if (is_object($formData[$detail->field]) && $formData[$detail->field]->getSize() > 0) {
                                $formData[$detail->field] = $this->storeFile($formData[$detail->field], $this->slug);
                            }
                        }
                    }
                }

                $this->model->updateData($id, $formData);

                if ($data->store_id != $formData['store_id']) {
                    $log = $this->model->getLastStatusByStoreId($formData['store_id']);

                    app(AuditRecord::class)->where('audit_route_id', $id)->delete();
                    $audit = $this->model->find($id);
                    $this->createAuditRecords($audit, $log);
                }

                DB::commit();
    
                return view('alerts.success',[
                    'msg'=>'資料更新成功',
                    'redirectURL'=>route($this->slug.'.index')
                ]);
            }

            DB::rollBack();

            return view('alerts.error', [
                'msg'=>'資料新增失敗, 無該功能項之細項設定',
                'redirectURL'=>route($this->slug.'.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return view('alerts.error',[
                'msg'=>$e->getMessage(),
                'redirectURL'=>route($this->prefix.'.index')
            ]);
        }
    }

    public function downloadReport(Request $request, $id) {
        $route = app(AuditRoute::class)->find($id);
       
        $info = $this->createPdf($id);
        
        return $info['pdf']->download(date('YmdHis').'_稽核報告書.pdf');
    }

    public function sendEmail(Request $request) {
        try {
            $this->createPdf($request->id);
            $route = app(AuditRoute::class)->find($request->id);

            if ($route->store) {
                $route->store->email = $request->email;
                $route->store->save();

                Mail::to($request->email)->send(new SendReport($route));
            }

            return view('alerts.success', [
                'msg'=>'稽核報告書已寄至商家成功',
                'redirectURL'=>route($this->slug.'.index')
            ]);
        } catch (\Exception $e) {
            return view('alerts.error',[
                'msg'=>$e->getMessage(),
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }
    }

    public function hasAuditCompleted(Request $request) {
        $logs = app(AuditRecord::class)
            ->where('audit_route_id', $request->id)
            ->where('status', 0)
            ->get();
        if ($logs->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => '尚有未檢查之稽核項目',
                'data' => null
            ], 400);
        }

        $msg = '';
        $pass = true;
        $logs = app(AuditRecord::class)
            ->where('audit_route_id', $request->id)
            ->where('status', 2)
            ->get();
        if ($logs->count() > 0) {
            foreach ($logs as $log) {
                if ($log->fails->count() == 0) {
                    $pass = false;
                    $msg .= '['.optional($log->regulation)->clause.'] 之缺失紀錄尚未填寫!!<br>';
                }
            }

            if (!$pass) {
                return response()->json([
                    'status' => false,
                    'message' => $msg,
                    'data' => null
                ], 400);
            }
        }

        $log = app(Counseling::class)->where('audit_route_id', $request->id)->first();
        if (!$log) {
            return response()->json([
                'status' => false,
                'message' => '自主輔導未填寫',
                'data' => null
            ], 400);
        }

        $logs = app(AuditRecord::class)
            ->where('audit_route_id', $request->id)
            ->get();
            
        if ($logs->count() > 0) {
            foreach ($logs as $log) {
                $regulation = $log->regulation;
                if ($regulation) {
                    $items = $regulation->items;

                    if ($items->count() > 0) {
                        $rows = app(AuditRecordItem::class)
                            ->where('audit_record_id', $log->id)
                            ->get();
                        if ($rows->count() < $items->count()) {
                            return response()->json([
                                'status' => false,
                                'message' => $regulation->clause.'之項目數值未填寫完整',
                                'data' => null
                            ], 400);
                        }
                    }
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => '全部項目已完成稽核',
            'data' => null
        ], 200);
    }

    public function hasPhotoUpload(Request $request) {
        $log = app(AuditRouteStoreImg::class)
            ->where('audit_route_id', $request->id)
            ->where('is_main', 1)
            ->first();

        if (!$log) {
            return response()->json([
                'status' => false,
                'message' => '請設定商家市招照片, 並設定一張為主照片',
                'data' => null
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => '已完成商家市招照片',
            'data' => null
        ], 200);
    }

    public function createAuditRecords($audit, $route = null) {
        $user = Auth::user();

        $mainAttributes = app(MainAttribute::class)
            ->where('system_type_id', $audit->system_type_id)
            ->orderBy('sort', 'ASC')
            ->get();

        $typeInfo = $audit->store->storeType->name;

        if ($audit->systemType->name == '食安系統') {
            if ($typeInfo == '小型') {
                $type = 2;
            } else {
                $type = 1;
            }
        } else {
            $type = 1;
        }

        if ($mainAttributes->count() > 0) {
            foreach ($mainAttributes as $attribute) {
                $regulations = $attribute->getRegulationsByType($type);
                if ( $regulations->count() > 0) {
                    foreach ($regulations as $regulation) {
                        $status = 0;
                        $record = null;

                        if (!empty($route)) {
                            $record = app(AuditRecord::class)
                                ->where('audit_route_id', $route->id)
                                ->where('store_id', $route->store_id)
                                ->where('regulation_id', $regulation->id)
                                ->where('status', 2)
                                ->first();
                        }

                        $recordId = app(AuditRecord::class)->create([
                            'id' => uniqid(),
                            'audit_route_id' => $audit->id,
                            'store_id' => $audit->store_id,
                            'main_attribute_id' => $regulation->main_attribute_id,
                            'sub_attribute_id' => $regulation->sub_attribute_id,
                            'regulation_id' => $regulation->id,
                            'audit_fail_type_id' => optional($record)->audit_fail_type_id,
                            'user_id' => $audit->main_user_id
                        ])->id;

                        if (!empty($record)) {
                            $check = app(PendingCheck::class)->create([
                                'id' => uniqid(),
                                'audit_route_id' => $audit->id,
                                'main_user_id' => $audit->main_user_id,
                                'sub_user_id' => $audit->sub_user_id,
                                'store_id' => $audit->store_id,
                                'fail_type_id' => $record->audit_fail_type_id,
                                'regulation_id' => $record->regulation_id,
                                'audit_day' => $audit->audit_day
                            ]);
                            if ($record->fails->count() > 0) {
                                foreach ($record->fails as $fail) {
                                    app(PendingCheckLog::class)->create([
                                        'id' => uniqid(),
                                        'pending_check_id' => $check->id,
                                        'audit_record_fail_id' => $fail->id,
                                        'regulation_fact_id' => $fail->regulation_fact_id,
                                        'img' => $fail->img,
                                        'note' => $fail->note
                                    ]);
                                }
                            }
                        }
    
                        if ($regulation->items->count() > 0) {
                            foreach ($regulation->items as $item) {
                                app(AuditRecordItem::class)->create([
                                    'id' => uniqid(),
                                    'audit_record_id' => $recordId,
                                    'regulation_item_id' => $item->id
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    private function createPdf($id) {
        $path = storage_path('app/public').'/pdfs';
        $filePath = 'pdfs/'.date('YmdHis').'_稽核報告書.pdf';

        File::makeDirectory($path, $mode = 0777, true, true);
        PDF::setOptions(['defaultFont' => 'ARIALUNI', 'fontDir'=>storage_path('app/public/fonts')]);

        $filters = [
            'audit_route_id' => $id
        ];
        $audit = app(AuditRoute::class)->getDataByFilters($filters);
        $audit->report = $filePath;
        $audit->save();

        $mainAttributes = app(MainAttribute::class)->orderBy('sort', 'ASC')->get();

        $pdf = PDF::loadView('pdfs.audit', [
            'audit'=>$audit,
            'mainAttributes'=>$mainAttributes,
            'auditRecord'=>app(AuditRecord::class),
            'fail'=>app(AuditRecordFail::class),
            'auditRecordItem'=>app(AuditRecordItem::class)
        ]);
        $pdf->save(storage_path('app/public').'/'.$filePath, 'UTF-8');

        return [
            'pdf'=>$pdf,
            'filePath'=>$filePath
        ];
    }
}
