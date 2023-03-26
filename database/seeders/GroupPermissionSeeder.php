<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Permission;
use App\Models\GroupPermission;
use Illuminate\Database\Seeder;

class GroupPermissionSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        app(GroupPermission::class)->truncate();

        foreach ($this->getData() as $data) {
            app(GroupPermission::class)->create($data);
        }
    }

    public function getData() {
        $arr = [];
        $permissions = app(Permission::class)->get();
        $groups = app(Group::class)->get();

        if ($groups->count() > 0 && $permissions->count() > 0) {
            foreach ($groups as $group) {
                if ($group->name == '系統管理') {
                    foreach ($permissions as $permission) {
                        array_push($arr, [
                            'id' => uniqid(),
                            'group_id' => $group->id,
                            'permission_id' => $permission->id
                        ]);
                    }
                }
                else
                {
                    $menus = ['目錄設定', '群組設定', '角色設定', '身份設定', '組織類型設定', '帳戶類別設定', '費率類別設定'];

                    foreach ($permissions as $permission) {
                        if ($permission->menu && !in_array($permission->menu->menu_name, $menus))
                        {
                            array_push($arr, [
                                'id' => uniqid(),
                                'group_id' => $group->id,
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
