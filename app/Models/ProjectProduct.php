<?php

namespace App\Models;

class ProjectProduct extends AbstractModel
{
    protected $table = 'project_products';
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
                    'model' => 'App\Models\FuncType',
                    'references_field' => 'id',
                    'type_code' => 'identity_types',
                    'show_field' => 'type_name'
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
                'field' => 'type',
                'type' => 'select',
                'show_name' => '類型',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'options' => json_encode([
                    ['text'=>'一般', 'value'=>1, 'default'=>0],
                    ['text'=>'範本', 'value'=>2, 'default'=>0],
                ])
            ],
            [
                'field' => 'sales_type_id',
                'type' => 'select',
                'show_name' => '銷售模式',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 2,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\FuncType',
                    'references_field' => 'id',
                    'type_code' => 'sales_types',
                    'show_field' => 'type_name'
                ]),
                'create_rule' => json_encode([
                    'sales_type_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'sales_type_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['sales_type_id.required'=>'請選擇銷售模式']
                ]),
            ],
            [
                'field' => 'effective_day',
                'type' => 'date',
                'show_name' => '生效日期',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 3,
                'create_rule' => json_encode([
                    'effective_day'=>'required'
                ]),
                'update_rule' => json_encode([
                    'effective_day'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['effective_day.required'=>'生效日期請勿空白']
                ]),
            ],
            [
                'field' => 'expiration_day',
                'type' => 'date',
                'show_name' => '截止日期',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 4,
                'create_rule' => json_encode([
                    'expiration_day'=>'required'
                ]),
                'update_rule' => json_encode([
                    'expiration_day'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['expiration_day.required'=>'截止日期請勿空白']
                ]),
            ],
            [
                'field' => 'pay_way',
                'type' => 'select',
                'show_name' => '付款方式',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 5,
                'options' => json_encode([
                    ['text'=>'先付', 'value'=>1, 'default'=>0],
                    ['text'=>'後付', 'value'=>2, 'default'=>0]
                ])
            ],
            [
                'field' => 'month_total',
                'type' => 'number',
                'show_name' => '月繳合計',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 6,
            ],
            [
                'field' => 'deposit_total',
                'type' => 'number',
                'show_name' => '保證金合計',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 7,
            ],
            [
                'field' => 'discount_set',
                'type' => 'text',
                'show_name' => '折抵設定',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 8,
            ],
            [
                'field' => 'promotion_type',
                'type' => 'select',
                'show_name' => '優惠類型',
                'join_search' => 1,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 9,
                'options' => json_encode([
                    ['text'=>'金額', 'value'=>1, 'default'=>0],
                    ['text'=>'百分比', 'value'=>2, 'default'=>0]
                ])
            ],
            [
                'field' => 'amount',
                'type' => 'number',
                'show_name' => '總價',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 10,
            ],
            [
                'field' => 'discount',
                'type' => 'text',
                'show_name' => '優惠(元/%)',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 11,
            ],
            [
                'field' => 'sender',
                'type' => 'text',
                'show_name' => '送件人',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 12,
                'create_rule' => json_encode([
                    'sender'=>'required'
                ]),
                'update_rule' => json_encode([
                    'sender'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['sender.required'=>'送件人請勿空白']
                ]),
            ],
            [
                'field' => 'agent_code',
                'type' => 'text',
                'show_name' => '經銷代碼',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 13,
                'create_rule' => json_encode([
                    'agent_code'=>'required'
                ]),
                'update_rule' => json_encode([
                    'agent_code'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['agent_code.required'=>'經銷代碼請勿空白']
                ]),
            ],
            [
                'field' => 'tel',
                'type' => 'text',
                'show_name' => '聯絡電話',
                'join_search' => 2,
                'required' => 2,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 14,
            ],
            [
                'field' => 'recipient',
                'type' => 'text',
                'show_name' => '收件人',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 15,
                'create_rule' => json_encode([
                    'recipient'=>'required'
                ]),
                'update_rule' => json_encode([
                    'recipient'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['recipient.required'=>'收件人請勿空白']
                ]),
            ],
            [
                'field' => 'engineer',
                'type' => 'text',
                'show_name' => '技術員',
                'join_search' => 1,
                'required' => 1,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 16,
                'create_rule' => json_encode([
                    'engineer'=>'required'
                ]),
                'update_rule' => json_encode([
                    'engineer'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['engineer.required'=>'技術員請勿空白']
                ]),
            ],
            [
                'field' => 'auditor',
                'type' => 'text',
                'show_name' => '稽核人',
                'join_search' => 1,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 17,
            ],
            [
                'field' => 'status',
                'type' => 'radio',
                'show_name' => '狀態',
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 18,
                'options' => json_encode([
                    ['text'=>'啟用', 'value'=>1, 'default'=>0],
                    ['text'=>'停用', 'value'=>2, 'default'=>1],
                ])
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 19
            ],
            [
                'field' => 'invalid_date',
                'type' => 'date_time',
                'show_name' => '停用日期',
                'browse' => 1,
                'sort' => 20
            ],
        ];
    }
}
