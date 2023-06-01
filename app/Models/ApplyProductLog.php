<?php

namespace App\Models;

class ApplyProductLog extends AbstractModel
{
    protected $table = 'apply_product_logs';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function apply()
    {
        return $this->belongsTo(Apply::class, 'apply_id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productType()
    {
        return $this->belongsTo(FuncType::class, 'product_type_id', 'id');
    }
    
    public function feeRateLogs()
    {
        return $this->hasMany(ApplyFeeRateLog::class, 'apply_product_log_id');
    }
}
