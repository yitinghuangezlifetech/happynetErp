<?php

namespace Database\Seeders;

use DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Group;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(User::class)->truncate();
    
        foreach ($this->getData() as $data) {
            app(User::class)->create($data);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function getData()
    {
        $arr = [];

        $group = app(Group::class)->where('name', '奕立生活科技有限公司')->first();
        $role = app(Role::class)
            ->where('group_id', $group->id)
            ->where('name', '超級管理員')
            ->first();
            
        if ($role)
        {
            array_push($arr, [
                'id' => uniqid(),
                'group_id' => $role->group_id,
                'role_id' => $role->id,
                'account' => 'admin',
                'password' => Hash::make('admin'),
                'name' => '超級管理員',
                'status' => 1
            ]);
        }
        
        return $arr;
    }
}
