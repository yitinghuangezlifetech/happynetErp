<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\GroupPermission;
use App\Models\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
    public function getGroupPermissionComponent(Request $request)
    {
        $user = app(User::class)->find($request->userId);
        $menuItems = app(Menu::class)
            ->whereNull('parent_id')
            ->orderBy('sort', 'ASC')
            ->get();

        $permissions = app(Permission::class)->get();

        if (($user->role->super_admin == 1 || ($user->group_id == $request->groupId)) && $request->type!='create')
        {
            $hasPermissions = $this->getOriginGroupPermissions($request->groupId);
        } 
        else
        {
            $hasPermissions = [];
        }

        $content = view('components.group_permissions', compact(
            'menuItems', 'permissions', 'hasPermissions', 'user'
        ))->render();

        return response()->json([
            'status'=>true,
            'message'=>'取得資料成功',
            'data'=>$content
        ], 200);
    }

    public function getRolePermissionComponent(Request $request) {
        $menuItems = app(Menu::class)
            ->whereNull('parent_id')
            ->orderBy('sort', 'ASC')
            ->get();

        $permissions = $this->getGroupPermissions($request->groupId);
        $hasPermissions = $this->getRolePermissions($request->roleID);

        $content = view('components.role_permissions', compact(
            'menuItems', 'permissions', 'hasPermissions'
        ))->render();

        return response()->json([
            'status'=>true,
            'message'=>'取得資料成功',
            'data'=>$content
        ], 200);
    }

    private function getOriginGroupPermissions($groupId)
    {
        $menus = [];
        $permissions = app(GroupPermission::class)
            ->where('group_id', $groupId)
            ->get();
        if ($permissions->count() > 0) 
        {
            foreach ($permissions as $permission) 
            {
                $menus[$permission->permission_id] = 1;
            }
        }
       
        return $menus;
    }

    private function getGroupPermissions($groupId)
    {
        $menus = [];
        $permissions = app(GroupPermission::class)
            ->where('group_id', $groupId)
            ->get();

        if ($permissions->count() > 0)
        {
            foreach ($permissions as $permission)
            {
                if ($permission->permission)
                {
                    try 
                    {
                        $menus[$permission->permission->menu->getParent->id][$permission->permission->menu_id][$permission->permission_id] = 1;
                    }
                    catch (\Exception $e)
                    {
                        $permission->permission->delete();
                    }
                }
            }
        }
       
        return $menus;
    }

    private function getRolePermissions($roleId)
    {
        $menus = [];
        $permissions = app(RolePermission::class)
            ->where('role_id', $roleId)
            ->get();

        if ($permissions->count() > 0)
        {
            foreach ($permissions as $permission)
            {
                $menus[$permission->permission_id] = 1;
            }
        }
       
        return $menus;
    }
}
