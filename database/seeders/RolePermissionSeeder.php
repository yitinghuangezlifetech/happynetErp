<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app(RolePermission::class)->truncate();

        foreach ($this->getData() as $data) {
            app(RolePermission::class)->create($data);
        }
    }

    public function getData()
    {
        $arr = [];
        $permissions = app(Permission::class)->get();
        $roles = app(Role::class)->get();

        $menus = ['目錄設定', '功能類別設定', '群組設定', '角色設定', '身份設定', '組織類型設定', '帳戶類別設定', '費率類別設定'];

        if ($roles->count() > 0 && $permissions->count() > 0) {
            foreach ($roles as $role) {
                if ($role->group->name == '系統管理') {
                    if ($role->super_admin == 1) {
                        foreach ($permissions as $permission) {
                            array_push($arr, [
                                'id' => uniqid(),
                                'role_id' => $role->id,
                                'permission_id' => $permission->id
                            ]);
                        }
                    } else {
                        foreach ($permissions as $permission) {
                            if ($permission->menu && !in_array($permission->menu->menu_name, $menus)) {
                                array_push($arr, [
                                    'id' => uniqid(),
                                    'role_id' => $role->id,
                                    'permission_id' => $permission->id
                                ]);
                            }
                        }
                    }
                } else {
                    foreach ($permissions as $permission) {
                        if ($permission->menu && !in_array($permission->menu->menu_name, $menus)) {
                            array_push($arr, [
                                'id' => uniqid(),
                                'role_id' => $role->id,
                                'permission_id' => $permission->id
                            ]);
                        }
                    }
                }
            }
        }

        return $arr;
    }
}
