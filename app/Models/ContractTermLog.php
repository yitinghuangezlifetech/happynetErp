<?php

namespace App\Models;

class ContractTermLog extends AbstractModel
{
    protected $table = 'contract_term_logs';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function term()
    {
        return $this->belongsTo(Term::class, 'term_id');
    }
}
