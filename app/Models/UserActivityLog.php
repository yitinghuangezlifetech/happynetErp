<?php

namespace App\Models;

class UserActivityLog extends AbstractModel
{
    protected $table = 'user_activity_logs';
    protected $guarded = [];

    public function getFieldProperties() 
    {
        return [
            [
                'field' => 'user_id',
                'type' => 'select',
                'show_name' => '所屬帳戶',
                'join_search' => 2,
                'required' => 2,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'sort' => 0,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'account'
                ])
            ],
            [
                'field' => 'action',
                'type' => 'text',
                'show_name' => '操作',
                'join_search' => 2,
                'required' => 2,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'sort' => 1
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 3
            ],
        ];
    }
}