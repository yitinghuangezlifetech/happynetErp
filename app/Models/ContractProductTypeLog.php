<?php

namespace App\Models;

class ContractProductTypeLog extends AbstractModel
{
    protected $table = 'contract_product_type_logs';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function productType()
    {
        return $this->belongsTo(FuncType::class, 'product_type_id', 'id');
    }

    public function productLogs()
    {
        return $this->hasMany(ContractProductLog::class, 'log_id');
    }
}
