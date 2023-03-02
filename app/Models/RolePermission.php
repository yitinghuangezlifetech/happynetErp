<?php

namespace App\Models;

class RolePermission extends AbstractModel
{
    protected $table = 'role_permissions';
    protected $guarded = [];

    public function getFieldProperties(){}

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
