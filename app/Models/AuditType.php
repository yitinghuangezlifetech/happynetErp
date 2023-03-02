<?php

namespace App\Models;

class AuditType extends AbstractModel
{
    protected $table = 'audit_types';
    protected $guarded = [];

    public function getFieldProperties() {
        return [
            [
                'field' => 'system_type_id',
                'type' => 'select',
                'show_name' => '所屬系統類別',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\SystemType',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'system_type_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'system_type_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['system_type_id.required'=>'請選擇所屬系統類別']
                ]),
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '稽核類型名稱',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1
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
