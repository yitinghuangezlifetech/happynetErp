<?php

namespace Database\Seeders;

use DB;
use App\Models\Group;
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
        return [
            [
                'id' => uniqid(),
                'name' => '系統管理'
            ],
        ];
    }
}