<?php

namespace App\Models;

class BounsGroup extends AbstractModel
{
    protected $table = 'bouns_groups';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'parent_id',
                'type' => 'select',
                'show_name' => '父層群組',
                'join_search' => 1,
                'required' => 2,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\BounsGroup',
                    'references_field' => 'id',
                    'show_field' => 'name',
                    'parent_id' => ''
                ])                
            ],
            [
                'field' => 'status',
                'type' => 'radio',
                'show_name' => '狀態',
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 2,
                'options' => json_encode([
                    ['text'=>'啟用', 'value'=>1, 'default'=>1],
                    ['text'=>'停用', 'value'=>2, 'default'=>0],
                ])
            ],
            [
                'field' => 'group_no',
                'type' => 'text',
                'show_name' => '奬金群組編號',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 3,
                'create_rule' => json_encode([
                    'group_no'=>'required'
                ]),
                'update_rule' => json_encode([
                    'group_no'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['group_no.required'=>'奬金群組編號請勿空白']
                ]),
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '奬金群組名稱',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 4,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'奬金群組名稱請勿空白']
                ]),
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 5
            ],
        ];
    }

    public function getChilds()
    {
        return $this->hasMany(BounsGroup::class, 'parent_id')
            ->orderBy('sort', 'ASC');
    }

    public function logs()
    {
        return $this->hasMany(BonusGroupLog::class, 'bonus_group_id');
    }
}
