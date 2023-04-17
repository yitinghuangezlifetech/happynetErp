<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Group;
use App\Models\RolePermission;
use App\Models\RoleSystemTypeLog;

class RoleController extends BasicController
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
        
        $filters = $this->getFilters($request);
        $groups = $this->getParentGroupByUser();

        try
        {
            $list = $this->model->getListByFilters($this->menu->menuDetails, $filters);
        }
        catch (\Exception $e)
        {
            $list = (new Collection([]))->paginate(20); 
        }

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
            'filters' => $filters,
            'list' => $list,
            'groups' => $groups
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
        if ($request->user()->role->super_admin != 1)
        {
            if ($request->user()->cannot('create_'.$this->slug,  $this->model))
            {
                return view('alerts.error', [
                    'msg' => '您的權限不足, 請洽管理人員開通權限',
                    'redirectURL' => route('dashboard')
                ]);
            }
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
        if ($request->user()->role->super_admin != 1)
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
                'redirectURL'=>route($this->slug.'.index')
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

    public function destroy(Request $request, $id)
    {

        if ($request->user()->role->super_admin != 1){
            if ($request->user()->cannot('delete_'.$this->slug,  $this->model))
            {
                return view('alerts.error', [
                    'msg' => '您的權限不足, 請洽管理人員開通權限',
                    'redirectURL' => route('dashboard')
                ]);
            }
        }

        try {

            $users = app(User::class)
                ->where('role_id', $id)
                ->where('status', 1)
                ->get();

            if ($users->count() > 0)
            {
                return view('alerts.error', [
                    'msg' => '該群組底下還有啟用狀態的帳戶,請先移除該群組底下的帳戶後再移除群組 ',
                    'redirectURL' => route('dashboard')
                ]);
            }

            $data = $this->model->getData($id);
            
            app(User::class)->where('role_id', $id)->delete();
            app(RolePermission::class)->where('role_id', $id)->delete();

            $this->model->deleteData($id);

            return view('alerts.success',[
                'msg'=>'資料刪除成功',
                'redirectURL'=>route($this->slug.'.index')
            ]);

        } 
        catch (\Exception $e)
        {
            return view('alerts.error',[
                'msg'=>$e->getMessage(),
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }
    }

    private function proccessPermissions($roleId, $permissions)
    {
        app(RolePermission::class)
            ->where('role_id', $roleId)
            ->delete();
            
        if (is_array($permissions) && count($permissions) >0)
        {
            foreach($permissions as $permission)
            {
                app(RolePermission::class)->create([
                    'id' => uniqid(),
                    'role_id' =>$roleId,
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
