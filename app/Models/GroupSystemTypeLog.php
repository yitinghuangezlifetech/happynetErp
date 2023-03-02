<?php

namespace App\Models;

class GroupSystemTypeLog extends AbstractModel
{
    protected $table = 'group_system_type_logs';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function systemType()
    {
        return $this->belongsTo(SystemType::class, 'system_type_id');
    }
}
