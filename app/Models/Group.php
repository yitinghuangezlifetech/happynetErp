<?php

namespace App\Models;

class Group extends AbstractModel
{
    protected $table = 'groups';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'systems',
                'type' => 'component',
                'show_name' => '所屬系統類別',
                'use_component' => 1,
                'component_name' => 'SystemType',
                'join_search' => 1,
                'required' => 1,
                'browse' => 2,
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
                'field' => 'parent_id',
                'type' => 'select',
                'show_name' => '父層群組',
                'join_search' => 1,
                'required' => 2,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 2,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Group',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])                
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '群組名稱',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 3,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'群組名稱請勿空白']
                ]),
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 4
            ],
        ];
    }

    public function getChilds()
    {
        return $this->hasMany(Group::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Group::class, 'parent_id');
    }

    public function permissions()
    {
        return $this->hasManyThrough(Permission::class, GroupPermission::class, 'group_id', 'id', 'id', 'permission_id');
    }

    public function roles()
    {
        return $this->hasMany(Role::class, 'group_id');
    }

    public function systemTypes()
    {
        return $this->hasManyThrough(SystemType::class, GroupSystemTypeLog::class, 'group_id', 'id', 'id', 'system_type_id');
    }
}
