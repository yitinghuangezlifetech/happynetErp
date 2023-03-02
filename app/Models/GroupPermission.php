<?php

namespace App\Models;

class GroupPermission extends AbstractModel
{
    protected $table = 'group_permissions';
    protected $guarded = [];

    public function getFieldProperties(){}

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
