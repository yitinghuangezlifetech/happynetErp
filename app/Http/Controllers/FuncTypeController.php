<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Collection;

use App\Models\FuncType;


class FuncTypeController extends BasicController
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
        $filters['type_code'] = ($request->type_code != $this->slug) ? $request->type_code : NULL;

        $list = (new Collection([]))->paginate($filters['rows']??20); 
        $data = $this->model->getDataByTypeCode($this->slug);

        if ($data)
        {
            $list = $data->getChilds($filters)->paginate($filters['rows']??20);
        }

        $this->indexView = 'func_type.sortable';

        return view($this->indexView, [
            'filters' => $filters,
            'parent_id' => $data->id??null,
            'list' => $list,
            'slug' => $this->slug
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

        $this->createView ='func_type.create';

        return view($this->createView,[
            'parent_id' => $request->parent_id,
            'slug' => $this->slug
        ]);
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
}
