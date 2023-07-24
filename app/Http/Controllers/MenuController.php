<?php

namespace App\Http\Controllers;

use DB;
use Artisan;
use Illuminate\Http\Request;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\GroupPermission;
use App\Models\RolePermission;

class MenuController extends BasicController
{
    public function index(Request $request)
    {
        $list = app(Menu::class)->whereNull('parent_id')
            ->orderBy('sort', 'ASC')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('menus.index', [
            'menu'=>$this->menu,
            'list'=>$list
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
                'redirectURL'=>route('menus.index')
            ]);
        }

        DB::beginTransaction();

        try {
            $formData = $request->except('_token');
            $formData['id'] = uniqid();

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

                $menu = $this->model->create($formData);
                DB::commit();

                $this->createPermission($menu);
                Artisan::call('db:seed --class=MenuDetailSeeder');
            
                return view('alerts.success', [
                    'msg'=>'資料新增成功',
                    'redirectURL'=>route('menus.index')
                ]);
            }

            DB::rollBack();

            return view('alerts.error', [
                'msg'=>'資料新增失敗, 無該功能項之細項設定',
                'redirectURL'=>route('menus.index')
            ]);

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            return view('alerts.error',[
                'msg'=>$e->getMessage(),
                'redirectURL'=>route('menus.index')
            ]);
        }
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
                'redirectURL'=>route('menus.index')
            ]);
        }

        DB::beginTransaction();

        try {
            $formData = $request->except('_token', '_method');

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

                $menu = $this->model->getData($id);

                DB::commit();

                $this->createOrUpdatePermission($menu);
                Artisan::call('db:seed --class=MenuDetailSeeder');
                
                return view('alerts.success',[
                    'msg'=>'資料更新成功',
                    'redirectURL'=>route('menus.index')
                ]);
            }

            DB::rollBack();

            return view('alerts.error', [
                'msg'=>'資料更新失敗, 無該功能項之細項設定',
                'redirectURL'=>route('menus.index')
            ]);
        }
        catch (\Exception $e)
        {
            DB::rollBack();

            return view('alerts.error',[
                'msg'=>$e->getMessage(),
                'redirectURL'=>route('menus.index')
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->cannot('delete_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => '/'
            ]);
        }

        try {
            $data = $this->model->getData($id);

            if ($data->getChilds->count() > 0)
            {
                return view('alerts.error',[
                    'msg'=>'該項目底下還有子項目, 請先移除子項目後再移除該項目',
                    'redirectURL'=>route('menus.index')
                ]);
            }

            $this->model->deleteData($id);

            return view('alerts.success',[
                'msg'=>'資料刪除成功',
                'redirectURL'=>route('menus.index')
            ]);

        } 
        catch (\Exception $e)
        {
            return view('alerts.error',[
                'msg'=>$e->getMessage(),
                'redirectURL'=>route('menus.index')
            ]);
        }
    }

    public function sort(Request $request)
    {
        if (count($request->data) > 0)
        {
            foreach ($request->data as $k1=>$data)
            {
                $menu = app(Menu::class)->where('id', $data['id'])->first();

                if ($menu)
                {
                    $menu->parent_id = NULL;
                    $menu->sort = $k1;
                    $menu->save();

                    $this->destroyPermission($menu);
                }

                if (isset($data['children']) && count($data['children']) > 0)
                {
                    foreach ($data['children'] as $k2=>$child) 
                    {
                        $childMenu = app(Menu::class)->where('id', $child['id'])->first();

                        if ($childMenu)
                        {
                            $childMenu->parent_id = $data['id'];
                            $childMenu->sort = $k2;
                            $childMenu->save();

                            $this->createPermission($childMenu);
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

    public function createPermission($menu)
    {
        $actions = ['browse', 'create', 'edit', 'show', 'update', 'delete'];

        if (empty($menu->slug) && is_null($menu->parent_id))
        {
            $this->destroyPermission($menu);
        }
        else
        {
            $permissions = app(Permission::class)->where('menu_id', $menu->id)->get();

            if ($permissions->count() == 0)
            {
                foreach ($actions as $action)
                {
                    app(Permission::class)->create([
                        'id' => uniqid(),
                        'menu_id' => $menu->id,
                        'code' => $action.'_'.$menu->slug
                    ]);
                }
            }
        }
    }

    public function createOrUpdatePermission($menu)
    {
        $permissions = app(Permission::class)->where('menu_id', $menu->id)->delete();

        $actions = ['browse', 'create', 'edit', 'show', 'update', 'delete'];

        foreach ($actions as $action)
        {
            $log = app(Permission::class)
                ->where('menu_id', $menu->id)
                ->where('code', $action.'_'.$menu->slug)
                ->first();
            
            if (!$log)
            {
                app(Permission::class)->create([
                    'id' => uniqid(),
                    'menu_id' => $menu->id,
                    'code' => $action.'_'.$menu->slug
                ]);
            }
        }
    }

    public function destroyPermission($menu)
    {
        $logs = app(Permission::class)->where('menu_id', $menu->id)->get();

        foreach ($logs??[] as $log)
        {
            app(GroupPermission::class)->where('permission_id', $log->id)->delete();
            app(RolePermission::class)->where('permission_id', $log->id)->delete();

            $log->delete();
        }
    }
}
