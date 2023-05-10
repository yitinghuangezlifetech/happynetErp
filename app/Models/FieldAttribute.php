<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldAttribute extends AbstractModel
{
    protected $table = 'field_attributes';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '屬性名稱',
                'use_edit_link'=>1,
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'屬性名稱請勿空白']
                ]),
            ],
            [
                'field' => 'val',
                'type' => 'text',
                'show_name' => '屬性值',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'create_rule' => json_encode([
                    'val'=>'required'
                ]),
                'update_rule' => json_encode([
                    'val'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['val.required'=>'屬性值請勿空白']
                ]),
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
            ],
        ];
    }
}
