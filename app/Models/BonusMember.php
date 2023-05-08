<?php

namespace App\Models;

class BonusMember extends AbstractModel
{
    protected $table = 'bonus_members';
    protected $guarded = [];

    public function getFieldProperties() 
    {
        return [
            [
                'field' => 'bonus_group_id',
                'type' => 'select',
                'show_name' => '所屬奬金群組',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\BounsGroup',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'bonus_group_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'bonus_group_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['bonus_group_id.required'=>'請選擇所屬奬金群組']
                ]),
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '成員名稱',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 2
            ],
            [
                'field' => 'bonus',
                'type' => 'text',
                'show_name' => '奬金%數',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 3
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 4
            ],
        ];
    }
}
