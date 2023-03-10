<?php

namespace Database\Seeders;

use DB;
use App\Models\Group;
use App\Models\SystemType;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(Group::class)->truncate();

        foreach ($this->getData() as $data)
        {
            app(Group::class)->create($data);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function getData()
    {
        $type = app(SystemType::class)->where('name', '資安系統')->first();

        return [
            [
                'id' => uniqid(),
                'system_type_id' => $type->id,
                'name' => '奕立生活科技有限公司'
            ],
        ];
    }
}