<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditFailType extends AbstractModel {
    use HasFactory;

    protected $table = 'audit_fail_types';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'system_type_id',
                'type' => 'select',
                'show_name' => '所屬系統類別',
                'use_component' => 2,
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
                'relationship_method'=>'systemTypes',
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '缺失名稱',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'缺失名稱請勿空白']
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

    public function hasRegulation($regulationId, $typeId) {
        return app(RegulationFail::class)->where('regulation_id', $regulationId)->where('audit_fail_type_id', $typeId)->first();
    }
}
