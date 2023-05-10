<?php

namespace Database\Seeders;

use DB;
use App\Models\FieldAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app(FieldAttribute::class)->truncate();
        
        foreach ($this->getData() as $data)
        {
            app(FieldAttribute::class)->create($data);
        }
    }

    private function getData()
    {
        return [
            [
                'id' => uniqid(),
                'name' => 'Input輸入欄位',
                'val' => 'text'
            ],
            [
                'id' => uniqid(),
                'name' => 'Radio選擇欄位',
                'val' => 'radio'
            ],
            [
                'id' => uniqid(),
                'name' => 'Checkbox選擇欄位',
                'val' => 'checkbox'
            ],
            [
                'id' => uniqid(),
                'name' => 'TextArea文字區塊',
                'val' => 'text_area'
            ],
            [
                'id' => uniqid(),
                'name' => 'Select下拉選單',
                'val' => 'select'
            ],
            [
                'id' => uniqid(),
                'name' => 'Image圖片上傳',
                'val' => 'image'
            ],
            [
                'id' => uniqid(),
                'name' => '簽名區塊',
                'val' => 'sign_area'
            ],
        ];
    }
}
