<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Models\FuncType;
use App\Models\FeeRateLog;
use Illuminate\Http\Request;

class FeeRateController extends BasicController
{
    public function create(Request $request)
    {
        if ($request->user()->cannot('create_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        if(view()->exists($this->slug.'.create'))
        {
            $this->createView = $this->slug.'.create';
        }

        $rateTypes = app(FuncType::class)->getChildsByTypeCode('rate_types');
        $callTargets = app(FuncType::class)->getChildsByTypeCode('call_targets');

        return view($this->createView, compact(
            'rateTypes', 'callTargets'
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
            $formData = $request->except('_token', 'rates');
            $formData['id'] = uniqid();
            $rates = $request->rates;

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

                $data = $this->model->create($formData);
                DB::commit();

                $this->proccessFeeRateLogs($data, $rates);
            
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

    private function proccessFeeRateLogs($main, $rates=[])
    {
        app(FeeRateLog::class)->where('fee_rate_id', $main->id)->delete();

        foreach ($rates??[] as $rate)
        {
            $rate['id'] = uniqid();
            $rate['fee_rate_id'] = $main->id;
            app(FeeRateLog::class)->create($rate);
        }
    }
}
