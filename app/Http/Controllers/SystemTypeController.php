<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

use App\Models\SystemType;
use App\Models\Regulation;
use App\Models\SystemTypeColum;

class SystemTypeController extends BasicController
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

        $colums = app(Regulation::class)->getFieldProperties();

        if(view()->exists($this->slug.'.create'))
        {
            $this->createView = $this->slug.'.create';
        }

        return view($this->createView, compact(
            'colums'
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
            $formData = $request->except('_token', 'colums');
            $formData['id'] = uniqid();
            $colums = $request->colums;

            $id = $this->model->createData($formData);
            DB::commit();

            $this->proccessColums($id, $colums);
        
            return view('alerts.success', [
                'msg'=>'資料新增成功',
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

    public function edit(Request $request, $id)
    {
        if ($request->user()->cannot('edit_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $data = app(SystemType::class)->find($id);
        $colums = app(Regulation::class)->getFieldProperties();
        $columVals = $this->getColumsSettings($data);

        if(view()->exists($this->slug.'.edit'))
        {
            $this->editView = $this->slug.'.edit';
        }

        return view($this->editView, compact(
            'colums', 'data', 'columVals'
        ));
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
            $formData = $request->except('_token', '_method', 'colums');
            $colums = $request->colums;

            $this->model->updateData($id, $formData);
            DB::commit();

            $this->proccessColums($id, $colums);

            return view('alerts.success',[
                'msg'=>'資料更新成功',
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

    private function getColumsSettings($data)
    {
        $arr = [];

        foreach ($data->colums??[] as $val)
        {
            $arr[$val->field] = $val->name;
        }

        return $arr;
    }

    private function proccessColums($id, $colums=[])
    {
        if(count($colums) > 0)
        {
            app(SystemTypeColum::class)->where('system_type_id', $id)->delete();

            foreach ($colums as $colum)
            {
                $colum['id'] = uniqid();
                $colum['system_type_id'] = $id;
                app(SystemTypeColum::class)->create($colum);
            }
        }
    }
}
