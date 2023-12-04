<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends AbstractModel
{
    use HasFactory;

    protected $table = 'groups';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'group_type_id',
                'type' => 'select',
                'show_name' => '群組類別',
                'use_edit_link' => 2,
                'join_search' => 1,
                'required' => 2,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\FuncType',
                    'references_field' => 'id',
                    'type_code' => 'group_types',
                    'show_field' => 'type_name'
                ]),
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
                'use_edit_link' => 1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'create_rule' => json_encode([
                    'name' => 'required'
                ]),
                'update_rule' => json_encode([
                    'name' => 'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required' => '群組名稱請勿空白']
                ]),
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
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
