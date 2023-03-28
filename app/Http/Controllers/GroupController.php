<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
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
        
        $list = $this->getParentGroupByUser();

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

    public function create(Request $request)
    {
        if ($request->user()->cannot('create_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $groups = $this->getParentGroupByUser();

        if(view()->exists($this->slug.'.create'))
        {
            $this->createView = $this->slug.'.create';
        }

        return view($this->createView, compact('groups'));
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
        $groups = $this->getParentGroupByUser();

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
            'groups' => $groups
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

    private function getParentGroupByUser()
    {
        $arr = [];
        $user = Auth::user();

        if ($user->role->super_admin == 1)
        {
            if ($user->group->name != '系統管理')
            {
                foreach ($user->group->getChilds??[] as $group)
                {
                    if (!in_array($group->parent->id, $arr))
                    {
                        array_push($arr, $group->parent->id);
                    }
                }

                $groups = app(Group::class)
                    ->whereNull('parent_id')
                    ->whereIn('id', $arr)
                    ->orderBy('sort', 'ASC')
                    ->orderBy('created_at', 'DESC')
                    ->get();
            }
            else
            {
                $groups = app(Group::class)
                    ->whereNull('parent_id')
                    ->orderBy('sort', 'ASC')
                    ->orderBy('created_at', 'DESC')
                    ->get();
            }
        }
        else
        {
            $groups = app(Group::class)
                ->whereNull('parent_id')
                ->where('id', $user->group->parent->id)
                ->orderBy('sort', 'ASC')
                ->orderBy('created_at', 'DESC')
                ->get();
        }

        return $groups;
    }
}
