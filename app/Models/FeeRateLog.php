<?php

namespace App\Models;

class FeeRateLog extends AbstractModel
{
    protected $table = 'fee_rate_logs';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function feeRate()
    {
        return $this->belongsTo(FeeRate::class, 'fee_rate_id');
    }

    public function target()
    {
        return $this->belongsTo(FuncType::class, 'call_target_id');
    }
}
