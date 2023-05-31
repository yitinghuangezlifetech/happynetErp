<?php

namespace App\Models;

class Apply extends AbstractModel
{
    protected $table = 'applies';
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
                    'apply_no'=>'required'
                ]),
                'update_rule' => json_encode([
                    'apply_no'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['apply_no.required'=>'申請編號請勿空白']
                ]),
            ],
            [
                'field' => 'contract_no',
                'type' => 'text',
                'show_name' => '合約編號',
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
                    ['contract_no.required'=>'合約編號請勿空白']
                ]),
            ],
            [
                'field' => 'contract_id',
                'type' => 'select',
                'show_name' => '適用合約',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 2,
                'browse' => 2,
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
                'required' => 2,
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
                'field' => 'contract_name',
                'type' => 'text',
                'show_name' => '合約名稱',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'create_rule' => json_encode([
                    'contract_name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'contract_name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['contract_name.required'=>'合約名稱請勿空白']
                ]),
            ],
            [
                'field' => 'identity_id',
                'type' => 'select',
                'show_name' => '客戶身份',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 2,
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
                    ['identity_id.required'=>'請選擇身份別']
                ]),
            ],
            [
                'field' => 'organization_id',
                'type' => 'select',
                'show_name' => '客戶所屬組織',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Organization',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'organization_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'organization_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['organization_id.required'=>'請選擇組織']
                ]),
            ],
            [
                'field' => 'user_id',
                'type' => 'select',
                'show_name' => '用戶帳號',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'account'
                ]),
                'create_rule' => json_encode([
                    'user_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'user_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['user_id.required'=>'請選擇用戶帳號']
                ]),
            ],
            [
                'field' => 'plan_type_id',
                'type' => 'select',
                'show_name' => '方案類別',
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
                'field' => 'apply_day',
                'type' => 'date',
                'show_name' => '申請日期',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'create_rule' => json_encode([
                    'apply_day'=>'required'
                ]),
                'update_rule' => json_encode([
                    'apply_day'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['apply_day.required'=>'請選擇申請日期']
                ]),
            ],
            [
                'field' => 'apply_type_id',
                'type' => 'select',
                'show_name' => '申請類別',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 2,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\FuncType',
                    'references_field' => 'id',
                    'type_code' => 'apply_types',
                    'show_field' => 'type_name'
                ])
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
                ])
            ],
            [
                'field' => 'start_day',
                'type' => 'date',
                'show_name' => '合約生效日',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 1,
                'browse' => 2,
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
                'show_name' => '合約終止日',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 1,
                'browse' => 2,
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
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
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
            ],
            [
                'field' => 'sender_id',
                'type' => 'select',
                'show_name' => '送件人',
                'join_search' => 1,
                'required' => 1,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'sender_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'sender_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['sender_id.required'=>'請選擇送件人']
                ]),
            ],
            [
                'field' => 'sender_sign',
                'type' => 'sign_area',
                'show_name' => '送件人簽名',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'recipient_id',
                'type' => 'select',
                'show_name' => '收件人',
                'join_search' => 1,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'create_show' => 2,
                'edit' => 1,
                'edit_show' => 2,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'recipient_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'recipient_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['recipient_id.required'=>'請選擇收件人']
                ]),
            ],
            [
                'field' => 'recipient_sign',
                'type' => 'sign_area',
                'show_name' => '收件人簽名',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'create_show' => 2,
                'edit' => 1,
                'edit_show' => 2,
            ],
            [
                'field' => 'technician_id',
                'type' => 'select',
                'show_name' => '技術員',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'create_show' => 2,
                'edit' => 1,
                'edit_show' => 2,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'technician_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'technician_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['technician_id.required'=>'請選擇技術員']
                ]),
            ],
            [
                'field' => 'technician_sign',
                'type' => 'sign_area',
                'show_name' => '技術員簽名',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'create_show' => 2,
                'edit' => 1,
                'edit_show' => 2,
            ],
            [
                'field' => 'auditor_id',
                'type' => 'select',
                'show_name' => '稽核員',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'create_show' => 2,
                'edit' => 1,
                'edit_show' => 2,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'auditor_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'auditor_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['auditor_id.required'=>'請選擇稽核員']
                ]),
            ],
            [
                'field' => 'auditor_sign',
                'type' => 'sign_area',
                'show_name' => '稽核員簽名',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'create_show' => 2,
                'edit' => 1,
                'edit_show' => 2,
            ],
            [
                'field' => 'system_no',
                'type' => 'text',
                'show_name' => '組織系統編號',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'customer',
                'type' => 'sign_area',
                'show_name' => '客戶簽名',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'tel',
                'type' => 'text',
                'show_name' => '聯絡電話',
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
                'field' => 'company_seal',
                'type' => 'image',
                'show_name' => '公司大章',
                'has_js' => 1,
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
                'has_js' => 1,
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
                'create_show' => 2,
                'edit' => 1,
                'edit_show' => 2,
            ],
            [
                'field' => 'status',
                'type' => 'select',
                'show_name' => '狀態',
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'options' => json_encode([
                    ['text'=>'通過', 'value'=>1, 'default'=>0],
                    ['text'=>'不通過', 'value'=>2, 'default'=>0],
                    ['text'=>'待審核', 'value'=>3, 'default'=>0],
                    ['text'=>'未送件', 'value'=>4, 'default'=>1],
                    ['text'=>'已送件', 'value'=>5, 'default'=>0],
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

    public function products()
    {
        return $this->hasMany(ApplyProductLog::class, 'apply_id');
    }

    public function terms()
    {
        return $this->hasMany(ApplyTermLog::class, 'apply_id')->orderBy('sort', 'ASC');
    }

    public function getSenderSignAttribute($img) {
        if ($img != '' && !is_null($img)) {
            return config('app.url').'/storage/'.$img;
        }
    }

    public function getRecipientSignAttribute($img) {
        if ($img != '' && !is_null($img)) {
            return config('app.url').'/storage/'.$img;
        }
    }

    public function getTechnicianSignAttribute($img) {
        if ($img != '' && !is_null($img)) {
            return config('app.url').'/storage/'.$img;
        }
    }

    public function getAuditorSignAttribute($img) {
        if ($img != '' && !is_null($img)) {
            return config('app.url').'/storage/'.$img;
        }
    }

    public function getCustomerAttribute($img) {
        if ($img != '' && !is_null($img)) {
            return config('app.url').'/storage/'.$img;
        }
    }

    public function getCompanySealAttribute($img) {
        if ($img != '' && !is_null($img)) {
            return config('app.url').'/storage/'.$img;
        }
    }

    public function getCompanyStampAttribute($img) {
        if ($img != '' && !is_null($img)) {
            return config('app.url').'/storage/'.$img;
        }
    }
}
