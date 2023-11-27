<?php

namespace App\Models;

class SecurityKey extends AbstractModel
{
    protected $table = 'security_keys';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'system_service_id',
                'type' => 'select',
                'show_name' => '服務別',
                'use_edit_link' => 2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\FuncType',
                    'references_field' => 'id',
                    'type_code' => 'system_service',
                    'show_field' => 'type_name'
                ]),
                'create_rule' => json_encode([
                    'system_service_id' => 'required'
                ]),
                'update_rule' => json_encode([
                    'system_service_id' => 'required'
                ]),
                'error_msg' => json_encode([
                    ['system_service_id.required' => '請選擇服務別']
                ]),
            ],
            [
                'field' => 'organization_id',
                'type' => 'select',
                'show_name' => '所屬組織',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Organization',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'organization_id' => 'required'
                ]),
                'update_rule' => json_encode([
                    'organization_id' => 'required'
                ]),
                'error_msg' => json_encode([
                    ['organization_id.required' => '請選擇所屬組織']
                ]),
            ],
            [
                'field' => 'security_key',
                'type' => 'text',
                'show_name' => '金鑰',
                'join_search' => 2,
                'required' => 1,
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
                'field' => 'start_day',
                'type' => 'date',
                'show_name' => '生效日',
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'end_day',
                'type' => 'date',
                'show_name' => '失效日',
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
            ],
        ];
    }
}
