<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Group;
use App\Models\FuncType;
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
            
            $types    = app(FuncType::class)->where('type_code', 'org_type')->first();
            
            $i = rand(0, 1);

            foreach ($types->getChilds??[] as $k=>$info)
            {
                if ($i == $k)
                {
                    $type = $info;
                }
            }   

            $group    = app(Group::class)->where('name', '!=', '系統管理')->inRandomOrder()->first();
            $identity = app(FuncType::class)->where('type_name', '=', $group->name)->first();

            $data->organization_type_id = $type->id;
            $data->identity_id = $identity->id;
            $data->group_id = $group->id;
            $data->parent_id = $parentId;
            $data->save();
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
