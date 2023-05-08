<?php

namespace App\Http\Controllers;

use DB;
use App\Models\FuncType;
use App\Models\BounsGroup;
use App\Models\BonusGroupLog;
use Illuminate\Http\Request;

class BonusGroupController extends BasicController
{
    public function index(Request $request)
    {
        $list = app(BounsGroup::class)->whereNull('parent_id')
            ->orderBy('sort', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('bouns_groups.index', [
            'list'=>$list
        ]);
    }

    public function create(Request $request)
    {
        if ($request->user()->cannot('create_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }
        
        $funcType = app(FuncType::class)->where('type_code', 'modules')->first();
        $funcTypes = $funcType->getChilds()->get();

        if(view()->exists($this->slug.'.create'))
        {
            $this->createView = $this->slug.'.create';
        }

        return view($this->createView, compact('funcTypes'));
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
            $formData = $request->except('_token', 'bonus');
            $formData['id'] = uniqid();

            $bonus = $request->bonus;

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

                $id = $this->model->createData($formData);
                DB::commit();

                $this->proccessBonus($id, $bonus);
            
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

    public function edit(Request $request, $id)
    {
        if ($request->user()->cannot('edit_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $funcType = app(FuncType::class)->where('type_code', 'modules')->first();
        $funcTypes = $funcType->getChilds()->get();

        $data = $this->model->find($id);
        $bonus = [];

        foreach ($data->logs??[] as $log)
        {
            $bonus[$log->func_type_id] = $log->bonus;
        }

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
            'funcTypes' => $funcTypes,
            'bonus' => $bonus
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
            $formData = $request->except('_token', '_method', 'bonus');
            $bonus = $request->bonus;

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

                $this->model->updateData($id, $formData);

                DB::commit();

                $this->proccessBonus($id, $bonus);
    
                return view('alerts.success',[
                    'msg'=>'資料更新成功',
                    'redirectURL'=>route($this->slug.'.index')
                ]);
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

    public function proccessBonus($id, $bonus=[])
    {
        if (!empty($bonus))
        {
            app(BonusGroupLog::class)->where('bonus_group_id', $id)->delete();

            foreach ($bonus as $data)
            {
                if (!empty($data['bonus']))
                {
                    $data['id'] = uniqid();
                    $data['bonus_group_id'] = $id;
    
                    app(BonusGroupLog::class)->create($data);
                }
            }
        }
    }

    public function sort(Request $request)
    {
        if (count($request->data) > 0)
        {
            foreach ($request->data as $k1=>$data)
            {
                $info = app(BounsGroup::class)->where('id', $data['id'])->first();

                if ($info)
                {
                    $info->parent_id = NULL;
                    $info->sort = $k1;
                    $info->save();
                }

                if (isset($data['children']) && count($data['children']) > 0)
                {
                    foreach ($data['children'] as $k2=>$child) 
                    {
                        $childInfo = app(BounsGroup::class)->where('id', $child['id'])->first();

                        if ($childInfo)
                        {
                            $childInfo->parent_id = $data['id'];
                            $childInfo->sort = $k2;
                            $childInfo->save();

                            if (isset($child['children']) && count($child['children']) > 0)
                            {
                                foreach ($child['children'] as $k3=>$treeChild)
                                {
                                    $threeLevel = app(BounsGroup::class)->where('id', $treeChild['id'])->first();

                                    if ($threeLevel)
                                    {
                                        $threeLevel->parent_id = $child['id'];
                                        $threeLevel->sort = $k3;
                                        $threeLevel->save();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return response()->json([
            'status'=>true,
            'message'=>'更新資料成功',
            'data'=>null
        ], 200);
    }
}
