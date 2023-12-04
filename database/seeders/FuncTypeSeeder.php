<?php

namespace Database\Seeders;

use DB;
use Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\FuncType;

class FuncTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app(FuncType::class)->truncate();

        if (Storage::disk('public')->exists('tableData/func_type.sql')) {
            DB::unprepared(Storage::disk('public')->get('tableData/func_type.sql'));
        }
    }
}
