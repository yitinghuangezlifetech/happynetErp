<?php

namespace App\Models;

class FeeRateType extends AbstractModel
{
    protected $table = 'fee_rate_rules';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '類別名稱',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'類別名稱請勿空白']
                ]),
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
