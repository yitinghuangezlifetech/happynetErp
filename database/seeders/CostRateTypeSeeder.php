<?php

namespace Database\Seeders;

use DB;
use App\Models\CostRateType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CostRateTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(CostRateType::class)->truncate();

        foreach ($this->getData() as $data)
        {
            app(CostRateType::class)->create($data);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function getData()
    {
        return [
            [
                'id' => uniqid(),
                'name' => '市話',
            ],
            [
                'id' => uniqid(),
                'name' => '長途',
            ],
            [
                'id' => uniqid(),
                'name' => '行動',
            ],
        ];
    }
}
