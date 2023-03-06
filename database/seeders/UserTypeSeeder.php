<?php

namespace Database\Seeders;

use DB;
use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(UserType::class)->truncate();

        foreach ($this->getData() as $data)
        {
            app(UserType::class)->create($data);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function getData()
    {
        return [
            [
                'id' => uniqid(),
                'name' => '主用戶',
            ],
            [
                'id' => uniqid(),
                'name' => '附掛用戶',
            ]
        ];
    }
}
