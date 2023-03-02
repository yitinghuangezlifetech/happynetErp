<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendingCheck extends AbstractModel {
    use HasFactory;

    protected $table = 'pending_checks';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function auditRoute() {
        return $this->belongsTo(AuditRoute::class, 'audit_route_id');
    }

    public function store() {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function failType() {
        return $this->belongsTo(AuditFailType::class, 'audit_fail_type_id');
    }

    public function regulation() {
        return $this->belongsTo(Regulation::class, 'regulation_id');
    }

    public function pendingCheckLogs() {
        return $this->hasMany(PendingCheckLog::class, 'pending_check_id');
    }

    public function successLogs() {
        return $this->hasMany(PendingCheckResult::class, 'pending_check_id')
            ->where('record_type', 1);
    }

    public function failLogs() {
        return $this->hasMany(PendingCheckResult::class, 'pending_check_id')
            ->where('record_type', 2);
    }

    public function getSuccessLogsByLogId($logId) {
        return $this->hasMany(PendingCheckResult::class, 'pending_check_id')
            ->where('record_type', 1)
            ->where('pending_check_log_id', $logId)
            ->get();
    }

    public function getFailLogsByLogId($logId) {
        return $this->hasMany(PendingCheckResult::class, 'pending_check_id')
            ->where('record_type', 2)
            ->where('pending_check_log_id', $logId)
            ->get();
    }

    public function items() {
        return $this->hasMany(PendingCheckItem::class, 'pending_check_id');
    }

    public function getOriginRecord($routeId, $regulationId) {
        return app(AuditRecord::class)
            ->where('audit_route_id', $routeId)
            ->where('regulation_id', $regulationId)
            ->first();
    }
}
