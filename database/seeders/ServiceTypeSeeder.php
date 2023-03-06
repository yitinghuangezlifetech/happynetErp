<?php

namespace Database\Seeders;

use DB;
use App\Models\ServiceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(ServiceType::class)->truncate();

        foreach ($this->getData() as $data)
        {
            app(ServiceType::class)->create($data);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function getData()
    {
        return [
            [
                'id' => uniqid(),
                'name' => '服務一',
            ],
            [
                'id' => uniqid(),
                'name' => '服務二',
            ],
        ];
    }
}
