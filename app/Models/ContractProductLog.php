<?php

namespace App\Models;

class ContractProductLog extends AbstractModel
{
    protected $table = 'contract_product_logs';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }
}
