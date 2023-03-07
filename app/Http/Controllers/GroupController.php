<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Group;
use App\Models\GroupPermission;
use App\Models\GroupSystemTypeLog;

class GroupController extends BasicController
{
    public function index(Request $request) 
    {
        if ($request->user()->cannot('browse_'.$this->slug,  $this->model)) 
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }
        
        $list = app(Group::class)
            ->whereNull('parent_id')
            ->orderBy('sort', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->get();

        if(view()->exists($this->slug.'.index')) 
        {
            $this->indexView = $this->slug.'.index';
        } 
        else 
        {
            if ($this->menu->sortable_enable == 1) 
            {
                $this->indexView = 'templates.sortable';
            }
            else
            {
                $this->indexView = 'templates.index';
            }
        }

        return view($this->indexView, [
            'list' => $list
        ]);
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

        $formData = $request->except('_token', '_method', 'permissions', 'systems');
        $formData['id'] = uniqid();
        $permissions = $request->permissions;

        $this->model->createData($formData);
        $this->proccessPermissions($formData['id'], $permissions);
        
        return view('alerts.success', [
            'msg'=>'資料新增成功',
            'redirectURL'=>route($this->slug.'.index')
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->user()->super_admin != 1)
        {
            if ($request->user()->cannot('update_'.$this->slug,  $this->model))
            {
                return view('alerts.error', [
                    'msg' => '您的權限不足, 請洽管理人員開通權限',
                    'redirectURL' => route('dashboard')
                ]);
            }
        }
        $validator = $this->updateRule($request->all());

        if (!is_array($validator) && $validator->fails())
        {
            return view('alerts.error',[
                'msg'=>$validator->errors()->all()[0],
                'redirectURL'=>route($this->prefix.'.index')
            ]);
        }
        $formData = $request->except('_token', '_method', 'permissions', 'systems');
        $permissions = $request->permissions;

        $this->model->updateData($id, $formData);
        $this->proccessPermissions($id, $permissions);

        return view('alerts.success',[
            'msg'=>'資料更新成功',
            'redirectURL'=>route($this->slug.'.index')
        ]);
    }

    private function proccessPermissions($groupId, $permissions)
    {
        app(GroupPermission::class)
            ->where('group_id', $groupId)
            ->delete();
        if (is_array($permissions) && count($permissions) >0)
        {
            foreach($permissions as $permission) {
                app(GroupPermission::class)->create([
                    'id' => uniqid(),
                    'group_id' =>$groupId,
                    'permission_id' => $permission
                ]);
            }
        }
    }
}
