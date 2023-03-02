<?php

namespace Database\Seeders;

use DB;
use App\Models\SystemType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(SystemType::class)->truncate();

        foreach ($this->getData() as $data) {
            app(SystemType::class)->create($data);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function getData()
    {
        return [
            [
                'id' => uniqid(),
                'name' => '資安系統'
            ],
            [
                'id' => uniqid(),
                'name' => '環安系統'
            ],
            [
                'id' => uniqid(),
                'name' => '衛安系統'
            ],
            [
                'id' => uniqid(),
                'name' => '勞安系統'
            ],
            [
                'id' => uniqid(),
                'name' => '工安系統'
            ],
            [
                'id' => uniqid(),
                'name' => '食安系統'
            ],
        ];
    }
}
