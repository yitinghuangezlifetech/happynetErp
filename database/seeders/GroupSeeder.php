<?php

namespace Database\Seeders;

use DB;
use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(Group::class)->truncate();

        foreach ($this->getData() as $data) {
            if ($data['id'] == 1) {
                $id = uniqid();
                $parent = null;
            } else if ($data['id'] > 1) {
                $parent = $id;
                $id = uniqid();
            }

            $data['id'] = $id;
            $data['parent_id'] = $parent;

            app(Group::class)->create($data);

            // $group->factory()->count(11)->create()->each(function($data, $i)use($group){

            //     $row = $i + 1;

            //     if ($row == 1)
            //     {
            //         session(['parent_id'=>$group->id]);
            //     }
            //     else if ($row % 3 == 0)
            //     {
            //         session(['parent_id'=>$data->id]);
            //     }

            //     if ($data->id == session('parent_id'))
            //     {
            //         $data->name = $data->name.'-'.$i;
            //         $data->parent_id = $group->id;
            //         $data->save();
            //     }
            //     else
            //     {
            //         $data->name = $data->name.'-'.$i;
            //         $data->parent_id = session('parent_id');
            //         $data->save();
            //     }
            // });
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function getData()
    {
        return [
            [
                'id' => 1,
                'name' => '系統管理',
            ],
            [
                'id' => 2,
                'name' => '系統商'
            ],
            [
                'id' => 3,
                'name' => '經銷商'
            ],
            [
                'id' => 4,
                'name' => '一般用戶'
            ],
        ];
    }
}
