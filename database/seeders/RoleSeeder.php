<?php

namespace Database\Seeders;

use DB;
use App\Models\Role;
use App\Models\Group;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(Role::class)->truncate();

        foreach ($this->getData() as $data) {
            app(Role::class)->create($data);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function getData()
    {
        $arr = [];
        $groups = app(Group::class)->get();

        if ($groups->count() > 0)
        {
            foreach ($groups as $group)
            {
                if ($group->name == '系統管理')
                {
                    array_push($arr, [
                        'id' => uniqid(),
                        'group_id' => $group->id,
                        'super_admin' => 1,
                        'name' => '超級管理員'
                    ]);
                }
                else if ($group->name == '系統商')
                {
                    array_push($arr, [
                        'id' => uniqid(),
                        'group_id' => $group->id,
                        'super_admin' => 2,
                        'name' => '系統商管理員'
                    ]);
                }
                else if ($group->name == '經銷商')
                {
                    array_push($arr, [
                        'id' => uniqid(),
                        'group_id' => $group->id,
                        'super_admin' => 2,
                        'name' => '經銷商管理員'
                    ]);
                }
                else if ($group->name == '一般用戶')
                {
                    array_push($arr, [
                        'id' => uniqid(),
                        'group_id' => $group->id,
                        'super_admin' => 2,
                        'name' => '一般用戶管理員'
                    ]);
                }
            }
        }

        return $arr;
    }
}
