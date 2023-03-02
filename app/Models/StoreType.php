<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class StoreType extends AbstractModel {
    use SoftDeletes;

    protected $table = 'store_types';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '商家分類名稱',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 1
            ],
        ];
    }

    public function stores()
    {
        return $this->hasMany(Store::class, 'store_type_id');
    }
}
