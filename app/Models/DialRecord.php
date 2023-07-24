<?php

namespace App\Models;

class DialRecord extends AbstractModel
{
    protected $table = 'dial_records';
    protected $guarded = [];

    public function getFieldProperties() {
        return [
            [
                'field' => 'batch_no',
                'type' => 'text',
                'show_name' => '匯入批號',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
                'create_rule' => json_encode([
                    'batch_no'=>'required'
                ]),
                'update_rule' => json_encode([
                    'batch_no'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['batch_no.required'=>'匯入批號請勿空白']
                ]),
            ],
            [
                'field' => 'company_code',
                'type' => 'text',
                'show_name' => '系統商代碼',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 2,
                'browse' => 2,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'dail_record_type_id',
                'type' => 'select',
                'show_name' => '資料類型',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\FuncType',
                    'references_field' => 'id',
                    'type_code' => 'dail_record_types',
                    'show_field' => 'type_name'
                ]),
                'create_rule' => json_encode([
                    'dail_record_type_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'idendail_record_type_idtity_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['dail_record_type_id.required'=>'請選擇通聯資料類型']
                ]),
            ],
            [
                'field' => 'user_id',
                'type' => 'select',
                'show_name' => '用戶',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'user_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'user_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['user_id.required'=>'請選擇用戶']
                ]),
            ],
            [
                'field' => 'telecom_account',
                'type' => 'text',
                'show_name' => '用戶帳號/代號',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
                'create_rule' => json_encode([
                    'telecom_account'=>'required'
                ]),
                'update_rule' => json_encode([
                    'telecom_account'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['telecom_account.required'=>'匯入批號請勿空白']
                ]),
            ],
            [
                'field' => 'attach_number',
                'type' => 'text',
                'show_name' => '附掛號碼',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 2,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'call_type',
                'type' => 'text',
                'show_name' => '撥打類型',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 2,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'tel_number',
                'type' => 'text',
                'show_name' => '電話號碼',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 2,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'source_ip',
                'type' => 'text',
                'show_name' => '來源IP',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 2,
                'browse' => 2,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'dial_location',
                'type' => 'text',
                'show_name' => '發話地',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 2,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'dial_number',
                'type' => 'text',
                'show_name' => '發話號碼',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 2,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'accept_location',
                'type' => 'text',
                'show_name' => '受話地',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 2,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'accept_number',
                'type' => 'text',
                'show_name' => '受話號碼',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 2,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'accept_IP',
                'type' => 'text',
                'show_name' => '受話IP',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 2,
                'browse' => 2,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'record_day',
                'type' => 'text',
                'show_name' => '通話日期-民國日期',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'record_day_ad',
                'type' => 'text',
                'show_name' => '通話日期-西元日期',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'start_time',
                'type' => 'text',
                'show_name' => '發話時間',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'end_time',
                'type' => 'text',
                'show_name' => '結束時間',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'talking_time',
                'type' => 'text',
                'show_name' => '通話時間',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'sec',
                'type' => 'text',
                'show_name' => '總通話秒數',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'frontent_code',
                'type' => 'text',
                'show_name' => '前置碼',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'period',
                'type' => 'text',
                'show_name' => '時段',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'fee',
                'type' => 'text',
                'show_name' => '通話費',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
            [
                'field' => 'note',
                'type' => 'text_area',
                'show_name' => '備註',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 2,
                'edit' => 2,
                'show' => 1,
            ],
        ];
    }
}