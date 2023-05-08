<?php

namespace App\Models;

class BonusGroupLog extends AbstractModel
{
    protected $table = 'bonus_group_logs';
    protected $guarded = [];

    public function getFieldProperties(){}

    public function funcType()
    {
        return $this->belongsTo(FuncType::class, 'func_type_id');
    }
}
