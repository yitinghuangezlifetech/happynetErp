<?php

namespace Database\Seeders;

use DB;
use App\Models\OrganizationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(OrganizationType::class)->truncate();

        foreach ($this->getData() as $data)
        {
            app(OrganizationType::class)->create($data);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function getData()
    {
        return [
            [
                'id' => uniqid(),
                'name' => '企業',
            ],
            [
                'id' => uniqid(),
                'name' => '個人',
            ]
        ];
    }
}
