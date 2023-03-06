<?php

namespace App\Models;

class FeeRate extends AbstractModel
{
    protected $table = 'fee_rates';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'identity_id',
                'type' => 'select',
                'show_name' => '身份',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Identity',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'identity_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'identity_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['identity_id.required'=>'請選擇身份']
                ]),
            ],
            [
                'field' => 'service_type_id',
                'type' => 'select',
                'show_name' => '服務類別',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Identity',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'identity_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'identity_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['identity_id.required'=>'請選擇身份']
                ]),
            ],
            [
                'field' => 'ip',
                'type' => 'text',
                'show_name' => 'IP',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'create_rule' => json_encode([
                    'ip'=>'required'
                ]),
                'update_rule' => json_encode([
                    'ip'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['ip.required'=>'IP請勿空白']
                ]),
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 2
            ],
        ];
    }
}
