<?php

namespace Database\Seeders;

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

        if (Storage::disk('public')->exists('tableData/func_type.txt'))
        {
            $content = json_decode(Storage::disk('public')->get('tableData/func_type.txt'), true);

            if (is_array($content))
            {
                foreach ($content as $data)
                {
                    app(FuncType::class)->create($data);
                }
            }
        }
    }
}
