<?php

namespace App\Models;

class RoleSystemTypeLog extends AbstractModel
{
    protected $table = 'role_system_type_logs';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function systemType()
    {
        return $this->belongsTo(SystemType::class, 'system_type_id');
    }
}
