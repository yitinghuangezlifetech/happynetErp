<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TopicType extends AbstractModel {
    use HasFactory;

    protected $table = 'topic_types';
    protected $guarded = [];

    public function getFieldProperties() {
        return [
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '屬性名稱',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0
            ],
            [
                'field' => 'val',
                'type' => 'text',
                'show_name' => '屬性值',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1
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
}
