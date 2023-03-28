<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Group;
use App\Models\Identity;
use App\Models\Organization;
use App\Models\OrganizationType;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(Organization::class)->truncate();

        app(Organization::class)->factory()->count(30)->create()->each(function($data, $i){
            
            $rows = $i + 1;

            if ($rows == 1 || $rows % 4 == 0)
            {
                $parentId = null;
                session(['parent_id'=>$data->id]);
            }
            else
            {
                $parentId = session('parent_id');
            }

            $type     = app(OrganizationType::class)->inRandomOrder()->first();
            $identity = app(Identity::class)->where('name', '!=', '系統管理')->inRandomOrder()->first();
            $group    = app(Group::class)->where('name', $identity->name)->first();

            $data->organization_type_id = $type->id;
            $data->identity_id = $identity->id;
            $data->group_id = $group->id;
            $data->parent_id = $parentId;
            $data->save();
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
