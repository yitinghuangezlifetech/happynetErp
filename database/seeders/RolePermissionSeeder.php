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

        foreach ($this->getData() as $data)
        {
            app(RolePermission::class)->create($data);
        }
    }

    public function getData()
    {
        $arr = [];
        $permissions = app(Permission::class)->get();
        $roles = app(Role::class)->get();

        if ($roles->count() > 0 && $permissions->count() > 0)
        {
            foreach ($roles as $role)
            {
                if ($role->group->name == '奕立生活科技有限公司')
                {
                    if($role->super_admin == 1)
                    {
                        foreach ($permissions as $permission)
                        {
                            array_push($arr, [
                                'id' => uniqid(),
                                'role_id' => $role->id,
                                'permission_id' => $permission->id
                            ]);
                        }
                    }
                    else
                    {

                        $category = [
                            '系統', ''
                        ];

                        foreach ($permissions as $permission)
                        {
                            if (!in_array($permission->menu->name, $category))
                            {
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
        }

        return $arr;
    }
}
