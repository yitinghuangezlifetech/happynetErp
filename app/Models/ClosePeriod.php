<?php

namespace App\Models;

class ClosePeriod extends AbstractModel
{
    protected $table = 'close_periods';
    protected $guarded = [];

    public function getFieldProperties() 
    {
        return [
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '結算區間名稱',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'結算區間名稱請勿空白']
                ]),
            ],
            [
                'field' => 'bill_day',
                'type' => 'number',
                'show_name' => '結算日',
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 2,
                'create_rule' => json_encode([
                    'bill_day'=>'required'
                ]),
                'update_rule' => json_encode([
                    'bill_day'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['bill_day.required'=>'結算日請勿空白']
                ]),
            ],
            [
                'field' => 'month_range',
                'type' => 'number',
                'show_name' => '結算月數',
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 3,
                'create_rule' => json_encode([
                    'month_range'=>'required'
                ]),
                'update_rule' => json_encode([
                    'month_range'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['month_range.required'=>'結算月數請勿空白']
                ]),
            ],
            [
                'field' => 'create_user_id',
                'type' => 'select',
                'show_name' => '建立人員',
                'join_search' => 2,
                'required' => 2,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'sort' => 4,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 5
            ],
            [
                'field' => 'update_user_id',
                'type' => 'select',
                'show_name' => '修改人員',
                'join_search' => 2,
                'required' => 2,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'sort' => 6,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'updated_at',
                'type' => 'date_time',
                'show_name' => '資料修改日期',
                'browse' => 1,
                'sort' => 7
            ],
        ];
    }
}
