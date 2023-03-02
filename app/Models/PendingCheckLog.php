<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendingCheckLog extends AbstractModel {
    use HasFactory;

    protected $table = 'pending_check_logs';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function pendingCheck() {
        return $this->belongsTo(PendingCheck::class, 'pending_check_id');
    }

    public function auditRecordFail() {
        return $this->belongsTo(AuditRecordFail::class, 'audit_record_fail_id');
    }

    public function regulationFact() {
        return $this->belongsTo(RegulationFact::class, 'regulation_fact_id');
    }
}
