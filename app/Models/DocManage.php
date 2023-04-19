<?php

namespace App\Models;

class DocManage extends AbstractModel
{
    protected $table = 'doc_manages';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'version_times',
                'type' => 'number',
                'show_name' => '版次',
                'join_search' => 2,
                'required' => 2,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,            
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
                'sort' => 2,
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
                'field' => 'contract_name',
                'type' => 'text',
                'show_name' => '合約名稱',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 3,
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
                'field' => 'customer_no',
                'type' => 'text',
                'show_name' => '用戶編號',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 4,
                'create_rule' => json_encode([
                    'customer_no'=>'required'
                ]),
                'update_rule' => json_encode([
                    'customer_no'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['customer_no.required'=>'用戶編號請勿空白']
                ]),
            ],
            [
                'field' => 'contract_file',
                'type' => 'file',
                'show_name' => '合約檔案',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 5,
                'create_rule' => json_encode([
                    'contract_file'=>'required'
                ]),
                'update_rule' => json_encode([
                    'contract_file'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['contract_file.required'=>'請上傳合約檔案']
                ]),
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 6
            ],
        ];
    }
}
