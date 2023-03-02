<?php

namespace Database\Seeders;

use DB;
use App\Models\StoreType;
use Illuminate\Database\Seeder;

class StoreTypeSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        app(StoreType::class)->truncate();

        foreach ($this->getData() as $data) {
            app(StoreType::class)->create($data);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function getData() {
        return [
            [
                'id' => uniqid(),
                'name' => '小型'
            ],
            [
                'id' => uniqid(),
                'name' => '中型'
            ],
            [
                'id' => uniqid(),
                'name' => '大型'
            ],
        ];
    }
}
