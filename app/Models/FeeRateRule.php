<?php

namespace App\Models;

class FeeRateRule extends AbstractModel
{
    protected $table = 'fee_rate_rules';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'fee_rate_type_id',
                'type' => 'select',
                'show_name' => '費率類別',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\FuncType',
                    'references_field' => 'id',
                    'type_code' => 'rate_types',
                    'show_field' => 'type_name'
                ]),
                'create_rule' => json_encode([
                    'fee_rate_type_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'fee_rate_type_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['fee_rate_type_id.required'=>'請選擇費率類別']
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
