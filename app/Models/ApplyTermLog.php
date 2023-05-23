<?php

namespace App\Models;

class ApplyTermLog extends AbstractModel
{
    protected $table = 'apply_term_logs';
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

    public function term()
    {
        return $this->belongsTo(Term::class, 'term_id');
    }
}
