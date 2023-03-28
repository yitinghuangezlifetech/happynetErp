<?php

namespace Database\Seeders;

use DB;
use App\Models\Identity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdentitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(Identity::class)->truncate();

        foreach ($this->getData() as $data)
        {
            app(Identity::class)->create($data);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function getData()
    {
        return [
            [
                'id' => uniqid(),
                'name' => '系統管理',
            ],
            [
                'id' => uniqid(),
                'name' => '系統商',
            ],
            [
                'id' => uniqid(),
                'name' => '經銷商',
            ],
            [
                'id' => uniqid(),
                'name' => '一般用戶',
            ],
        ];
    }
}
