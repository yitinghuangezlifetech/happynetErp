<?php

namespace Database\Seeders;

use DB;
use App\Models\Role;
use App\Models\Group;
use App\Models\SystemType;
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
        $type = app(SystemType::class)->where('name', '資安系統')->first();
        $groups = app(Group::class)->get();

        if ($groups->count() > 0)
        {
            foreach ($groups as $group)
            {
                if ($group->name == '奕立生活科技有限公司')
                {
                    array_push($arr, [
                        'id' => uniqid(),
                        'system_type_id' => $type->id,
                        'group_id' => $group->id,
                        'super_admin' => 1,
                        'name' => '超級管理員'
                    ]);
                }
            }
        }

        return $arr;
    }
}
