<?php

namespace Database\Seeders;

use DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Group;
use App\Models\UserType;
use App\Models\Organization;
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

        $group = app(Group::class)->where('name', '系統管理')->first();

        foreach ($group->roles ?? [] as $role) {
            if ($role->name == '超級管理員') {
                $organization = app(Organization::class)->where('name', '樂得網路電信科技有限公司')->first();

                array_push($arr, [
                    'id' => uniqid(),
                    'organization_id' => $organization->id,
                    'group_id' => $role->group_id,
                    'role_id' => $role->id,
                    'account' => 'admin',
                    'password' => Hash::make('admin'),
                    'name' => '超級管理員',
                    'telecom_number' => fake()->numberBetween($min = 10000000, $max = 99999999),
                    'status' => 1
                ]);
            }
        }

        $organizations = app(Organization::class)->get();

        foreach ($organizations ?? [] as $organization) {
            $group = $organization->group;

            if ($group) {
                foreach ($group->roles ?? [] as $role) {
                    for ($i = 1; $i <= 1; $i++) {
                        $userType = app(UserType::class)->inRandomOrder()->first();

                        array_push($arr, [
                            'id' => uniqid(),
                            'group_id' => $role->group_id,
                            'role_id' => $role->id,
                            'organization_id' => $organization->id,
                            'user_type_id' => $userType->id,
                            'account' => fake()->unique()->word,
                            'password' => Hash::make('1111'),
                            'name' => fake()->name,
                            'telecom_number' => fake()->numberBetween($min = 10000000, $max = 99999999),
                            'status' => 1,
                            'create_user_id' => '641da01e270e2',
                            'update_user_id' => '641da01e270e2',
                        ]);
                    }
                }
            }
        }

        return $arr;
    }
}
