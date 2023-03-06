<?php

namespace App\Models;

class Role extends AbstractModel
{
    protected $table = 'roles';
    protected $guarded = [];

    public function getFieldProperties() 
    {
        return [
            [
                'field' => 'group_id',
                'type' => 'select',
                'show_name' => '所屬群組',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Group',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'group_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'group_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['group_id.required'=>'請選擇所屬群組']
                ]),
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '角色名稱',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 2
            ],
            [
                'field' => 'has_audit_route',
                'type' => 'radio',
                'show_name' => '觀看所有稽核行程',
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 3,
                'options' => json_encode([
                    ['text'=>'是', 'value'=>1, 'default'=>0],
                    ['text'=>'否', 'value'=>2, 'default'=>1],
                ])
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

    public function permissions()
    {
        return $this->hasManyThrough(Permission::class, RolePermission::class, 'role_id', 'id', 'id', 'permission_id');
    }
    
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
    
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    public function systemType()
    {
        return $this->hasOneThrough(SystemType::class, RoleSystemTypeLog::class, 'role_id', 'id', 'id', 'system_type_id');
    }

    public function systemTypes()
    {
        return $this->hasManyThrough(SystemType::class, RoleSystemTypeLog::class, 'role_id', 'id', 'id', 'system_type_id');
    }

    public function getParentSystemType()
    {
        return $this->hasManyThrough(SystemType::class, GroupSystemTypeLog::class, 'group_id', 'id', 'id', 'system_type_id')
            ->where('group_system_type_logs.group_id', $this->group_id);
    }
}
