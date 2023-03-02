<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Menu;
use App\Models\Image;
use App\Models\AuditRoute;
use App\Models\AuditRecord;
use App\Models\AuditFailType;
use App\Models\AuditRecordFail;
use App\Models\AuditRecordItem;
use App\Models\MainAttribute;
use App\Models\Regulation;
use App\Models\PendingCheck;
use App\Models\SubAttribute;
use App\Models\AuditRecordSuccess;
use App\Models\AuditRouteStoreImg;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Traits\FileServiceTrait as fileService;

class AuditRecordController extends BasicController {
    use fileService;
    
    private $routeId;

    public function index($routeId) {
        $this->routeId = $routeId;
        $this->setBreadcrumb();
        
        $auditRoute = app(AuditRoute::class)->find($routeId);

        if ($auditRoute) {

            if ($auditRoute->status == 0) {
                $auditRoute->status = 2;
                $auditRoute->save();
            }

            $typeInfo = $auditRoute->store->storeType->name;

            if (Auth::user()->role->systemType->name == '食安系統') {
                if ($typeInfo == '小型') {
                    $type = 2;
                } else {
                    $type = 1;
                }
            } else {
                $type = 1;
            }

            $user = Auth::user();

            $mainImage = app(AuditRouteStoreImg::class)
                ->where('audit_route_id', $routeId)
                ->where('store_id', $auditRoute->store_id)
                ->where('is_main', 1)
                ->first();
            
            $mainAttributes = app(MainAttribute::class)
                ->where('system_type_id', $user->role->system_type_id)
                ->orderBy('sort', 'ASC')
                ->get();

            $pendingChecks = app(PendingCheck::class)
                ->where('audit_route_id', $routeId)
                ->where('store_id', $auditRoute->store_id)
                ->get();

            $pendings = app(PendingCheck::class)
                ->where('audit_route_id', $routeId)
                ->where('store_id', $auditRoute->store_id)
                ->where('status', 0)
                ->get();

            return view('audit_records.index', compact(
                'auditRoute', 'mainImage', 'mainAttributes', 'type', 
                'pendingChecks', 'pendings'
            ));
        }

        return view('alerts.error', [
            'msg'=>'該稽核行程不存在',
            'redirectURL'=>route('audit_routes.index')
        ]);
    }

    public function photos($routeId) {
        $this->routeId = $routeId;
        $this->setBreadcrumb();

        $route = app(AuditRoute::class)->find($routeId);

        $photos = app(AuditRouteStoreImg::class)
            ->where('audit_route_id', $route->id)
            ->get();

        return view('audit_records.photos', compact(
            'route', 'routeId', 'photos'
        ));
    }

    public function uploadPhoto(Request $request, $routeId) {

        $route = app(AuditRoute::class)->find($routeId);
        if (isset($request->img) && is_object($request->img) && $request->img->getSize() > 0) {
            $formData = $request->except('_token');
            $formData['id'] = uniqid();
            $formData['audit_route_id'] = $routeId;
            $formData['store_id'] = $route->store_id;
            $formData['img'] = $this->storeFile($request->img, 'stores');

            app(AuditRouteStoreImg::class)->create($formData);
        }

        return view('alerts.success', [
            'msg'=>'商家照片上傳成功',
            'redirectURL'=>route('audit_records.photos', $routeId)
        ]);
    }

    public function setMainPhoto(Request $request) {

        app(AuditRouteStoreImg::class)->where('audit_route_id', $request->routeId)->update([
            'is_main' => 2
        ]);

        $log = app(AuditRouteStoreImg::class)->find($request->id);

        if($log) {
            $log->is_main = 1;
            $log->save();
        }
    }

    public function regulations($routeId, $mainAttributeId) {
        $this->routeId = $routeId;
        $this->setBreadcrumb();

        $list = [];
        $data = [];
        $checkArr = [];
        $user = Auth::user();
        $route = app(AuditRoute::class)->find($routeId);
        $importantFailType = app(AuditFailType::class)->where('system_type_id', $user->role->system_type_id)->first();
        $mainFailType = app(AuditFailType::class)->where('system_type_id', $user->role->system_type_id)->first();

        if ($route) {
            $typeInfo = $route->store->storeType->name;

            if (Auth::user()->role->systemType->name == '食安系統') {
                if ($typeInfo == '小型') {
                    $storeType = 2;
                } else {
                    $storeType = 1;
                }
            } else {
                $storeType = 0;
            }

            $subAttributes = app(SubAttribute::class)
                ->where('main_attribute_id', $mainAttributeId)
                ->orderBy('sort', 'ASC')
                ->get();
            
            if ($subAttributes->count() > 0) {
                foreach ($subAttributes as $k=>$sub) {
                    $regulations = $sub->getRegulationByStoreType($storeType);

                    if ($regulations->count() > 0) {
                        foreach ($regulations as $k2=>$regulation) {
                            $record = app(AuditRecord::class)
                                ->where('audit_route_id', $routeId)
                                ->where('sub_attribute_id', $sub->id)
                                ->where('regulation_id', $regulation->id)
                                ->first();
                            if ($record) {
                                $list[$k]['id'] = $sub->id;
                                $list[$k]['name'] = $sub->name;
                                $list[$k]['regulations'][$k2]['id'] = $record->id;
                                $list[$k]['regulations'][$k2]['name'] = $regulation->clause;
                                $list[$k]['regulations'][$k2]['is_import'] = $regulation->is_import;
                                $list[$k]['regulations'][$k2]['is_main'] = $regulation->is_main;
                                $list[$k]['regulations'][$k2]['status'] = $record->status;
                                $list[$k]['regulations'][$k2]['audit_fail_type_id'] = $record->audit_fail_type_id;
                                $list[$k]['regulations'][$k2]['fail_types'] = [];

                                if($record->regulation->failTypes->count() > 0) {
                                    foreach($record->regulation->failTypes as $type) {
                                        array_push($list[$k]['regulations'][$k2]['fail_types'], [
                                            'id'=>$type->id,
                                            'name'=>$type->name
                                        ]);
                                    }
                                }
            
                                if ($record->items->count() > 0) {
                                    $list[$k]['regulations'][$k2]['items'] = [];
        
                                    foreach ($record->items as $k1=>$item) {
                                        if(!empty($item->value)) {
                                            $list[$k]['regulations'][$k2]['items'][$k1]['id'] = $item->id;
                                            $list[$k]['regulations'][$k2]['items'][$k1]['name'] = ($item->regulationItem)?$item->regulationItem->name:'';
                                            $list[$k]['regulations'][$k2]['items'][$k1]['value'] = $item->value;
                                        }
                                    }
                                    if (count($list[$k]['regulations'][$k2]['items']) == 0) {
                                        $list[$k]['regulations'][$k2]['items'] = [];
                                    }
                                } else {
                                    $list[$k]['regulations'][$k2]['items'] = [];
                                }
            
                                if ($record->regulation) {
                                    if ($record->regulation->items->count() > 0) {
                                        foreach($record->regulation->items as $k1=>$item) {
                                            $list[$k]['regulations'][$k2]['original_items'][$k1]['id'] = $item->id; 
                                            $list[$k]['regulations'][$k2]['original_items'][$k1]['name'] = $item->name; 
                                        }
                                    } else {
                                        $list[$k]['regulations'][$k2]['original_items'] = [];
                                    }
                                } else {
                                    $list[$k]['regulations'][$k2]['original_items'] = [];
                                }
            
                                if ($record->regulation->facts->count() > 0) {
                                    foreach ($record->regulation->facts as $k1=>$fact) {
                                        $list[$k]['regulations'][$k2]['facts'][$k1]['id'] = $fact->id;
                                        $list[$k]['regulations'][$k2]['facts'][$k1]['name'] = $fact->name;
                                    }
                                } else {
                                    $list[$k]['regulations'][$k2]['facts'] = [];
                                }
            
                                if ($record->fails->count() > 0) {
                                    foreach ($record->fails as $k1=>$fail) {
                                        $list[$k]['regulations'][$k2]['fails'][$k1]['id'] = $fail->id;
                                        $list[$k]['regulations'][$k2]['fails'][$k1]['regulation_fact_id'] = $fail->regulation_fact_id;
                                        $list[$k]['regulations'][$k2]['fails'][$k1]['img'] = $fail->img;
                                        $list[$k]['regulations'][$k2]['fails'][$k1]['note'] = $fail->note;
                                    }
                                } else {
                                    $list[$k]['regulations'][$k2]['fails'] = [];
                                }
                            }    
                        }
                    }
                }
            }
            
            $regulationModel = app(Regulation::class);
            return view('audit_records.regulations', compact('list', 'routeId', 'regulationModel', 'importantFailType', 'mainFailType', 'route'));
        }

        return view('alerts.error',[
            'msg'=>'該稽核行程不存在',
            'redirectURL'=>route('audit_routes.index')
        ]);
    }

    public function changeStatus(Request $request) {
        $validator = Validator::make($request->all(), [
            'routeId' => 'required',
            'record_id' => 'required',
            'status' => 'required',
        ],[
            'routeId.required' => '缺少稽核行程id',
            'record_id.required' => '缺少稽核紀錄id',
            'status.required' => '缺少稽核狀態',
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()[0],
                'data' => null
            ], 200);
        }

        $route = app(AuditRoute::class)->find($request->routeId);

        if ($route) {

            if ($route->status == 0) {
                $route->status = 2;
                $route->save();
            }

            $data = app(AuditRecord::class)->find($request->record_id);
            if ($data) {
                $data->status = $request->status;
                $data->save();

                return response()->json([
                    'status' => true,
                    'message' => '稽核狀態異動成功',
                    'data' => null
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => '無此稽核紀錄',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => false,
            'message' => '無此稽核行程',
            'data' => null
        ], 404);
    }

    public function changeFailType(Request $request) {
        $validator = Validator::make($request->all(), [
            'routeId' => 'required',
            'record_id' => 'required',
            'audit_fail_type_id' => 'required',
        ],[
            'routeId.required' => '缺少稽核行程id',
            'record_id.required' => '缺少稽核紀錄id',
            'audit_fail_type_id.required' => '缺少缺失項目',
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()[0],
                'data' => null
            ], 200);
        }

        $route = app(AuditRoute::class)->find($request->routeId);

        if ($route) {

            $data = app(AuditRecord::class)->find($request->record_id);
            if ($data) {
                if(empty($request->audit_fail_type_id)) {
                    $type = NULL;
                } else {
                    $type = $request->audit_fail_type_id;
                }
                
                $data->audit_fail_type_id = $type;
                $data->save();

                return response()->json([
                    'status' => true,
                    'message' => '缺失狀態異動成功',
                    'data' => null
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => '無此稽核紀錄',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => false,
            'message' => '無此稽核行程',
            'data' => null
        ], 404);
    }

    public function removePhoto(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ],[
            'id.required' => '缺少照片紀錄id',
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()[0],
                'data' => null
            ], 200);
        }
        $data = app(AuditRouteStoreImg::class)->find($request->id);

        if ($data) {
            $this->deleteFile($data->img);
        }

        app(AuditRouteStoreImg::class)->where('id', $request->id)->delete();

        return response()->json([
            'status' => true,
            'message' => '照片刪除成功',
            'data' => null
        ], 200);
    }

    public function getItems($routeId, $recordId) {
        $record = app(AuditRecord::class)->find($recordId);

        if ($record) {
            if ($record->regulation) {
                if ($record->regulation->items->count() > 0) {
                    $items = [];

                    if ($record->items->count() > 0) {
                        foreach($record->items as $item) {
                            $items[$item->regulation_item_id] = $item->value;
                        }
                    }

                    return view('audit_records.items', [
                        'record'=>$record,
                        'originItems'=>$record->regulation->items,
                        'items'=>$items
                    ]);
                }
            }

            return view('alerts.error',[
                'msg'=>'該條文目無需填寫之項目',
                'redirectURL'=>route('audit_records.regulations', ['routeId'=>$record->audit_route_id, 'mainAttributeId'=>$record->main_attribute_id])
            ]);
        }

        return view('alerts.error',[
            'msg'=>'該條文記錄不存在',
            'redirectURL'=>route('audit_records.regulations', ['routeId'=>$record->audit_route_id, 'mainAttributeId'=>$record->main_attribute_id])
        ]);
    }

    public function recordItems(Request $request) {
        $items = $request->items;

        if (count($items) > 0) {
            foreach($items as $item) {
                $log = app(AuditRecordItem::class)
                    ->where('audit_record_id', $request->record_id)
                    ->where('regulation_item_id', $item['regulation_item_id'])
                    ->first();
                if ($log) {
                    $log->value = $item['value'];
                    $log->save();
                } else {
                    app(AuditRecordItem::class)->create([
                        'id' => uniqid(),
                        'audit_record_id' => $request->record_id,
                        'regulation_item_id' => $item['regulation_item_id'],
                        'value' => $item['value']
                    ]);
                }
            }
        }

        $data = app(AuditRecord::class)->find($request->record_id);

        return view('alerts.success', [
            'msg'=>'資料更新成功',
            'redirectURL'=>route('audit_records.regulations', ['routeId'=>$data->audit_route_id, 'mainAttributeId'=>$data->main_attribute_id])
        ]);
    }

    public function failPage($routeId, $recordId) {
        $this->routeId = $routeId;
        $this->setBreadcrumb();

        $items = [];
        $types = app(AuditFailType::class)->all();
        $record = app(AuditRecord::class)->find($recordId);
        $route = app(AuditRoute::class)->find($routeId);
        
        if ($record) {
            if ($record->regulation->items->count() > 0) {
                if ($record->items->count() > 0) {
                    foreach($record->items as $item) {
                        $items[$item->regulation_item_id] = $item->value;
                    }
                }
            }

            return view('audit_records.fail', [
                'route'=>$route,
                'record'=>$record,
                'types'=>$types,
                'items'=>$items
            ]);
        }

        return view('alerts.error',[
            'msg'=>'該條文記錄不存在',
            'redirectURL'=>route('audit_records.index', $routeId)
        ]);
    }

    public function recordFails(Request $request) {
        $record = app(AuditRecord::class)->find($request->record_id);
        $fails = $request->fails??[];
        $items = $request->items??[];

        if (count($fails) > 0) {
            foreach ($fails as $fail) {
                if (isset($fail['id'])) {
                    if (isset($fail['img']) && is_object($fail['img']) && $fail['img']->getSize() > 0) {
                        $fail['img'] = $this->storeFile($fail['img'], 'stores');
                    }
                    app(AuditRecordFail::class)->where('id', $fail['id'])->update($fail);
                } else {
                    $fail['id'] = uniqid();
                    $fail['audit_route_id'] = $record->audit_route_id;
                    $fail['audit_record_id'] = $record->id;

                    if (isset($fail['img']) && is_object($fail['img']) && $fail['img']->getSize() > 0) {
                        $fail['img'] = $this->storeFile($fail['img'], 'stores');
                    }

                    app(AuditRecordFail::class)->create($fail);
                }
            }
        }

        if (count($items) > 0) {
            foreach($items as $item) {
                $log = app(AuditRecordItem::class)->where('regulation_item_id', $item['regulation_item_id'])->first();
                if ($log) {
                    $log->value = $item['value'];
                    $log->save();
                } else {
                    app(AuditRecordItem::class)->create([
                        'id' => uniqid(),
                        'audit_record_id' => $record['id'],
                        'regulation_item_id' => $item['regulation_item_id'],
                        'value' => $item['value']
                    ]);
                }
            }
        }

        return view('alerts.success', [
            'msg'=>'資料更新成功',
            'redirectURL'=>route('audit_records.regulations', ['routeId'=>$record->audit_route_id, 'mainAttributeId'=>$record->main_attribute_id])
        ]);
    }

    public function recordFailItems(Request $request, $routeId) {
        $records = $request->records;
        $fails = $request->fails;
        $success = $request->success;

        if (is_array($fails) && count($fails) > 0) {
            foreach($records as $record) {
                $log = app(AuditRecord::class)->find($record);
                $log->status = 2;
                $log->save();
            }

            foreach ($fails as $recordId=>$items) {
                foreach($items as $item) {
                    if (isset($item['id'])) {
                        if (isset($item['img']) && is_object($item['img']) && $item['img']->getSize() > 0) {
                            $item['img'] = $this->storeFile($item['img'], 'stores');
                        }
                        app(AuditRecordFail::class)->where('id', $item['id'])->update($item);
                    } else {
                        $item['id'] = uniqid();
                        $item['audit_route_id'] = $routeId;
                        $item['audit_record_id'] = $recordId;

                        if (isset($item['img']) && is_object($item['img']) && $item['img']->getSize() > 0) {
                            $item['img'] = $this->storeFile($item['img'], 'stores');
                        }

                        app(AuditRecordFail::class)->create($item);
                    }
                }
            }
        }

        if (is_array($success) && count($success) > 0) {
            foreach ($success as $recordId=>$items) {
                foreach($items as $item) {
                    if (isset($item['id'])) {
                        if (isset($item['img']) && is_object($item['img']) && $item['img']->getSize() > 0) {
                            $item['img'] = $this->storeFile($item['img'], 'stores');
                        }
                        app(AuditRecordSuccess::class)->where('id', $item['id'])->update($item);
                    } else {
                        $item['id'] = uniqid();
                        $item['audit_route_id'] = $routeId;
                        $item['audit_record_id'] = $recordId;

                        $log = app(AuditRecord::class)->find($recordId);
                        $log->status = 1;
                        $log->save();

                        if (isset($item['img']) && is_object($item['img']) && $item['img']->getSize() > 0) {
                            $item['img'] = $this->storeFile($item['img'], 'stores');
                        }

                        app(AuditRecordSuccess::class)->create($item);
                    }
                }
            }
        }

        return view('alerts.success', [
            'msg'=>'資料更新成功',
            'redirectURL'=>route('audit_records.index', $routeId)
        ]);
    }

    public function removeFailRecord(Request $request) {
        $fail = app(AuditRecordFail::class)->find($request->id);

        if ($fail) {
            $this->deleteFile($fail->img);
            $fail->delete();
        }
    }

    public function finishRecord($routeId) {
        $data = app(AuditRoute::class)->find($routeId);
        $fails = app(AuditRecordFail::class)
            ->where('audit_route_id', $routeId)
            ->get();

        return view('audit_records.finish', compact('data', 'routeId', 'fails'));
    }

    public function uploadRecord(Request $request, $routeId) {
        $data = $request->except('_token');

        
        if (isset($data['main_user_signe']) && !empty($data['main_user_signe'])) {
            $data['main_user_signe'] = $this->storeBase64($data['main_user_signe'], 'audit_routes', date('Ymd').uniqid().'.svg');
        }
        if (isset($data['sub_user_signe']) && !empty($data['sub_user_signe'])) {
            $data['sub_user_signe'] = $this->storeBase64($data['sub_user_signe'], 'audit_routes', date('Ymd').uniqid().'.svg');
        }
        if (isset($data['store_signe']) && !empty($data['store_signe'])) {
            $data['store_signe'] = $this->storeBase64($data['store_signe'], 'audit_routes', date('Ymd').uniqid().'.svg');
        }
        if (isset($data['gov_signe']) && !empty($data['gov_signe'])) {
            $data['gov_signe'] = $this->storeBase64($data['gov_signe'], 'audit_routes', date('Ymd').uniqid().'.svg');
        }

        $fails = app(AuditRecordFail::class)
            ->where('audit_route_id', $routeId)
            ->get();
        
        if($fails->count() > 0) {
            $data['audit_status'] = 1;
        }

        $route = app(AuditRoute::class)->find($routeId);

        if ($route) {
            if($route->audit_status == 2) {
                $data['audit_status'] = 2;
            }
        }

        app(AuditRoute::class)->where('id', $routeId)->update($data);

        $route = app(AuditRoute::class)->find($routeId);
        $photo = app(AuditRouteStoreImg::class)
            ->where('audit_route_id', $route->id)
            ->where('is_main', 1)
            ->first();

        return view('audit_records.preview', compact('routeId','route', 'photo'));
    }

    public function failPreview($routeId) {
        $types = app(AuditFailType::class)->all();
        $route = app(AuditRoute::class)->find($routeId);

        return view('audit_records.fail_preview', compact(
            'route','types','routeId'
        ));
    }

    public function completed($routeId) {
        $route = app(AuditRoute::class)->find($routeId);

        if ($route) {
            $route->status = 4;
            $route->save();
        }

        return view('alerts.success', [
            'msg'=>'資料已上傳成功',
            'redirectURL'=>route('audit_routes.index')
        ]);
    }

    public function getFailItems($routeId) {
        $types = app(AuditFailType::class)->all();
        $route = app(AuditRoute::class)->find($routeId);

        return view('audit_records.fail_items', compact(
            'route','types','routeId'
        ));
    }

    public function setBreadcrumb() {
        $parentName = $this->menu->getParent->menu_name;
    
        if (!empty($this->routeId)) {
            $this->breadcrumbs = [
                'mainMenu' => $parentName,
                'routeName' => $this->slug,
                'breadcrumb' => [
                    ['name' => $parentName, 'active' => false, 'breadUrl' => route( $this->slug.'.index' )],
                    ['name' => '稽核報告首頁', 'active' => true, 'breadUrl' => route('audit_records.index', $this->routeId)],
                ]
            ];

            view()->share($this->breadcrumbs);
        }
    }
}
