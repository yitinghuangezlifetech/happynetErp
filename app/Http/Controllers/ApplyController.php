<?php

namespace App\Http\Controllers;

use DB;
use PDF;
use File;
use Storage;
use Illuminate\Http\Request;

use App\Models\FuncType;
use App\Models\Project;
use App\Models\Contract;
use App\Models\Product;
use App\Models\ApplyTermLog;
use App\Models\ApplyProductLog;
use App\Models\ApplyFeeRateLog;

use App\Http\Controllers\Traits\FileServiceTrait as fileService;

class ApplyController extends BasicController
{
    use fileService;

    public function create(Request $request)
    {
        if ($request->user()->cannot('create_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $types = app(FuncType::class)->getChildsByTypeCode('product_types');
        $planTypes = app(FuncType::class)->getChildsByTypeCode('plan_types');
        $applyTypes = app(FuncType::class)->getChildsByTypeCode('apply_types');

        $contracts = app(Contract::class)->getActiveContracts();
        $projects = app(Project::class)->getActiveProjects();

        if(view()->exists($this->slug.'.create'))
        {
            $this->createView = $this->slug.'.create';
        }

        return view($this->createView, compact(
            'types', 'planTypes', 'applyTypes',
            'contracts', 'projects'
        ));
    }

    public function store(Request $request)
    {
        if ($request->user()->cannot('create_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $validator = $this->createRule($request->all());

        if (!is_array($validator) && $validator->fails())
        {
            return view('alerts.error',[
                'msg'=>$validator->errors()->all()[0],
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }

        DB::beginTransaction();

        try {
            $formData = $request->except('_token', 'products', 'regulations');
            $formData['id'] = uniqid();
            $products = $request->products;
            $regulations = $request->regulations;

            if ($this->model->checkColumnExist('create_user_id'))
            {
                $formData['create_user_id'] = Auth::user()->id;
            }

            if ($this->menu->menuDetails->count() > 0)
            {
                foreach ($this->menu->menuDetails as $detail)
                {
                    if (isset($formData[$detail->field]))
                    {
                        if ($detail->type == 'image' || $detail->type == 'file')
                        {
                            if (is_object($formData[$detail->field]) && $formData[$detail->field]->getSize() > 0)
                            {
                                $formData[$detail->field] = $this->storeFile($formData[$detail->field], $this->slug);
                            }
                        }
                    }
                }

                if (isset($formData['sender_sign']) && !empty($formData['sender_sign'])) {
                    $formData['sender_sign'] = $this->storeBase64($formData['sender_sign'], 'applies', date('Ymd').uniqid().'.jpg');
                }

                if (isset($formData['customer']) && !empty($formData['customer'])) {
                    $formData['customer'] = $this->storeBase64($formData['customer'], 'applies', date('Ymd').uniqid().'.jpg');
                }

                $data = $this->model->create($formData);
                DB::commit();

                $this->proccessProducts($data, $products);
                $this->proccessRegulations($data, $regulations);
            
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

        } 
        catch (\Exception $e)
        {
            DB::rollBack();

            return view('alerts.error',[
                'msg'=>$e->getMessage(),
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->model->find($id);
        $logs = $this->getProductLog($data);
        $obj = app(Product::class);
        $termTypes = app(FuncType::class)->getChildsByTypeCode('term_types');
        $types = app(FuncType::class)->getChildsByTypeCode('product_types');


        if (!$data)
        {
            return view('alerts.error',[
                'msg'=>'資料不存在',
                'redirectURL'=>route($this->slug.'.index')
            ]); 
        }

        if(view()->exists($this->slug.'.show'))
        {
            $this->editView = $this->slug.'.show';
        }

        return view($this->editView, [
            'data'=>$data,
            'id'=>$id,
            'logs'=>$logs,
            'obj'=>$obj,
            'types'=>$types,
            'termTypes'=>$termTypes,
        ]);
    }

    public function edit(Request $request, $id)
    {
        if ($request->user()->cannot('edit_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $data = $this->model->find($id);
        $applyLogs = $this->getProductLog($data);
        $obj = app(Product::class);
        $termTypes = app(FuncType::class)->getChildsByTypeCode('term_types');
        $types = app(FuncType::class)->getChildsByTypeCode('product_types');

        if (!$data)
        {
            return view('alerts.error',[
                'msg'=>'資料不存在',
                'redirectURL'=>route($this->slug.'.index')
            ]); 
        }

        if(view()->exists($this->slug.'.edit'))
        {
            $this->editView = $this->slug.'.edit';
        }

        return view($this->editView, [
            'data'=>$data,
            'id'=>$id,
            'applyLogs'=>$applyLogs,
            'obj'=>$obj,
            'types'=>$types,
            'termTypes'=>$termTypes,
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->user()->cannot('update_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }
        $validator = $this->updateRule($request->all());

        if (!is_array($validator) && $validator->fails())
        {
            return view('alerts.error',[
                'msg'=>$validator->errors()->all()[0],
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }

        DB::beginTransaction();

        try {
            $formData = $request->except('_token', '_method', 'products');
            $products = $request->products;

            if ($this->model->checkColumnExist('update_user_id'))
            {
                $formData['update_user_id'] = Auth::user()->id;
            }

            if ($this->menu->menuDetails->count() > 0)
            {
                foreach ($this->menu->menuDetails as $detail)
                {
                    if (isset($formData[$detail->field]))
                    {
                        if ($detail->type == 'image' || $detail->type == 'file')
                        {
                            if (is_object($formData[$detail->field]) && $formData[$detail->field]->getSize() > 0)
                            {
                                $formData[$detail->field] = $this->storeFile($formData[$detail->field], $this->slug);
                            }
                        }
                    }
                }

                if (isset($formData['sender_sign']) && !empty($formData['sender_sign'])) {
                    $formData['sender_sign'] = $this->storeBase64($formData['sender_sign'], 'applies', date('Ymd').uniqid().'.svg');
                }

                if (isset($formData['customer']) && !empty($formData['customer'])) {
                    $formData['customer'] = $this->storeBase64($formData['customer'], 'applies', date('Ymd').uniqid().'.svg');
                }

                $this->model->updateData($id, $formData);

                DB::commit();

                $data = $this->model->find($id);
                $this->proccessProducts($data, $products);

                if ($formData['status'] == 5)
                {
                    return view('alerts.success',[
                        'msg'=>'送件成功',
                        'redirectURL'=>route($this->slug.'.index')
                    ]);
                }
                else
                {
                    return view('alerts.success',[
                        'msg'=>'資料更新成功',
                        'redirectURL'=>route($this->slug.'.index')
                    ]);
                }
            }

            DB::rollBack();

            return view('alerts.error', [
                'msg'=>'資料更新失敗, 無該功能項之細項設定',
                'redirectURL'=>route($this->slug.'.index')
            ]);
        } 
        catch (\Exception $e)
        {
            DB::rollBack();

            return view('alerts.error',[
                'msg'=>$e->getMessage(),
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }
    }

    public function print($id)
    {
        $data = $this->model->find($id);
        $applyLogs = $this->getProductLog($data);
        $obj = app(Product::class);

        $path = storage_path('app/public').'/pdfs';
        $filePath = 'pdfs/'.$data->apply_no.'_申請書.pdf';

        File::makeDirectory($path, $mode = 0777, true, true);
        PDF::setOptions(['defaultFont' => 'ARIALUNI', 'fontDir'=>storage_path('app/public/fonts')]);

        $pdf = PDF::loadView('applies.pdf', [
            'data'=>$data,
            'id'=>$id,
            'applyLogs'=>$applyLogs,
            'obj'=>$obj,
        ]);

        $pdf->save(storage_path('app/public').'/'.$filePath, 'UTF-8');
        return $pdf->download(date('YmdHis').'_'.$data->apply_no.'_申請書.pdf');
    }

    private function proccessProducts($apply, $products=[])
    {
        if (!empty($products))
        {
            app(ApplyProductLog::class)->where('apply_id', $apply->id)->delete();
            app(ApplyFeeRateLog::class)->where('apply_id', $apply->id)->delete();

            foreach ($products??[] as $info)
            {
                if(isset($info['product_id']))
                {
                    if (isset($info['feeRates']))
                    {
                        $info['id'] = uniqid();
                        $info['apply_id'] = $apply->id;
                        $feeRates = $info['feeRates'];
                        unset($info['feeRates']);

                        $log = app(ApplyProductLog::class)->create($info);

                        foreach($feeRates??[] as $item)
                        {
                            $item['id'] = uniqid();
                            $item['apply_id'] = $apply->id;
                            $item['apply_product_log_id'] = $log->id;

                            app(ApplyFeeRateLog::class)->create($item);
                        }
                    }
                    else
                    {
                        if ($info['qty'] > 0)
                        {
                            $info['id'] = uniqid();
                            $info['apply_id'] = $apply->id;
                            app(ApplyProductLog::class)->create($info);
                        }
                    }
                }
            }
        }
    }

    public function getProductLog($apply)
    {
        $arr = [];

        foreach ($apply->productLogs??[] as $log)
        {
            if ($log->qty > 0) {
                $arr[$log->product_type_id][$log->product_id]['qty'] = $log->qty;
                $arr[$log->product_type_id][$log->product_id]['rent_month'] = $log->rent_month;
                $arr[$log->product_type_id][$log->product_id]['discount'] = $log->discount;
                $arr[$log->product_type_id][$log->product_id]['amount'] = $log->amount;
                $arr[$log->product_type_id][$log->product_id]['security_deposit'] = $log->security_deposit;
                $arr[$log->product_type_id][$log->product_id]['note'] = $log->note;
            } else {
                foreach($log->feeRateLogs??[] as $k=>$rate)
                {
                    $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['call_target_id'] = $rate->call_target_id;
                    $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['call_rate'] = $rate->call_rate;
                    $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['discount'] = $rate->discount	;
                    $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['amount'] = $rate->amount;
                    $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['charge_unit'] = $rate->charge_unit;
                    $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['parameter'] = $rate->parameter;
                }
            }
        }

        return $arr;
    }
}
