<?php

namespace App\Models;

class Apply extends AbstractModel
{
    protected $table = 'contracts';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'apply_no',
                'type' => 'text',
                'show_name' => '申請編號',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'create_rule' => json_encode([
                    'contract_no'=>'required'
                ]),
                'update_rule' => json_encode([
                    'contract_no'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['contract_no.required'=>'申請編號請勿空白']
                ]),
            ],
            [
                'field' => 'plan_type_id',
                'type' => 'select',
                'show_name' => '申請類別',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\FuncType',
                    'references_field' => 'id',
                    'type_code' => 'plan_types',
                    'show_field' => 'type_name'
                ]),
                'create_rule' => json_encode([
                    'plan_type_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'plan_type_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['plan_type_id.required'=>'請選擇申請類別']
                ]),
            ],
            [
                'field' => 'contract_id',
                'type' => 'text',
                'show_name' => '適用合約',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 2,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Contract',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
            ],
            [
                'field' => 'project_id',
                'type' => 'select',
                'show_name' => '適用專案',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Project',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
            ],
            [
                'field' => 'identity_id',
                'type' => 'select',
                'show_name' => '對象',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
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
                    ['identity_id.required'=>'請選擇適用對象']
                ]),
            ],
            [
                'field' => 'close_period_id',
                'type' => 'select',
                'show_name' => '結算區間',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\ClosePeriod',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'close_period_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'close_period_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['close_period_id.required'=>'請選擇結算區間']
                ]),
            ],
            [
                'field' => 'start_day',
                'type' => 'date',
                'show_name' => '生效日',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'create_rule' => json_encode([
                    'start_day'=>'required'
                ]),
                'update_rule' => json_encode([
                    'start_day'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['start_day.required'=>'生效日請勿空白']
                ]),
            ],
            [
                'field' => 'end_day',
                'type' => 'date',
                'show_name' => '截止日',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'create_rule' => json_encode([
                    'end_day'=>'required'
                ]),
                'update_rule' => json_encode([
                    'end_day'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['end_day.required'=>'截止日請勿空白']
                ]),
            ],
            [
                'field' => 'month_pay_total',
                'type' => 'number',
                'show_name' => '月繳合計',
                'join_search' => 2,
                'required' => 2,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'deposit_total',
                'type' => 'number',
                'show_name' => '保證金合計',
                'join_search' => 2,
                'required' => 2,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'discount_type',
                'type' => 'select',
                'show_name' => '優惠類型',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'options' => json_encode([
                    ['text'=>'金額', 'value'=>1, 'default'=>0],
                    ['text'=>'百分比', 'value'=>2, 'default'=>0],
                ])
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
            ],
            [
                'field' => 'sender',
                'type' => 'text',
                'show_name' => '送件人',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'agent_code',
                'type' => 'text',
                'show_name' => '經銷代碼',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'tel',
                'type' => 'text',
                'show_name' => '聯絡電話(ex:02-87902300)',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'empty',
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'recipient_name',
                'type' => 'text',
                'show_name' => '收件人',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'recipient',
                'type' => 'sign_area',
                'show_name' => '收件人簽名',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'technician_name',
                'type' => 'text',
                'show_name' => '技術員',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'technician',
                'type' => 'sign_area',
                'show_name' => '技術員簽名',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'auditor_name',
                'type' => 'text',
                'show_name' => '稽核員',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'auditor',
                'type' => 'sign_area',
                'show_name' => '稽核員簽名',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'customer_name',
                'type' => 'text',
                'show_name' => '用戶名稱',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'customer',
                'type' => 'sign_area',
                'show_name' => '用戶簽名',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'company_seal',
                'type' => 'image',
                'show_name' => '公司大章',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'company_stamp',
                'type' => 'image',
                'show_name' => '公司小章',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'fail_response',
                'type' => 'text_area',
                'show_name' => '審核不過理由',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 2,
                'edit' => 1,
            ],
            [
                'field' => 'status',
                'type' => 'select',
                'show_name' => '狀態',
                'browse' => 1,
                'create' => 2,
                'edit' => 1,
                'options' => json_encode([
                    ['text'=>'通過', 'value'=>1, 'default'=>0],
                    ['text'=>'不通過', 'value'=>2, 'default'=>0],
                    ['text'=>'待審核', 'value'=>3, 'default'=>0],
                    ['text'=>'新建單', 'value'=>4, 'default'=>1],
                ])
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
