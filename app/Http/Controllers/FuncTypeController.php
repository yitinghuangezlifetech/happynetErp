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

        $list = (new Collection([]))->paginate(20); 
        $filters = $this->getFilters($request);
        $parent_id;
        $filters['slug'] = $this->slug;

        $orderBy='created_at'; 
        $sort='DESC';
        $querySysFunc = FuncType::query()->where('type_code', $filters['slug'])->first();
        // dd($querySysFunc->id .' '. $querySysFunc->parent_id .' '. $filters['slug']);
        if(!empty($filters['slug']) && !is_null($querySysFunc)) {
          
            $parent_id= $querySysFunc->id;

            $queryList = app(FuncType::class)->newModelQuery();
            $queryList->where('parent_id', $querySysFunc->id)
            // $queryList->where('status', 1)
            ->get();

            if ($queryList->count() > 0)
            {
                $list = $queryList->paginate($filters['rows']??10);
            }
        }
        $this->indexView ='func_type.index';
        if ($this->menu->sortable_enable == 1) 
        {
            $this->indexView = 'func_type.sortable';
        }
        return view($this->indexView, [
            'parent_id' => $parent_id,
            'filters' => $filters,
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

        $this->createView ='func_type.create';

        return view($this->createView,['parent_id' => $request->parent_id]);
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
