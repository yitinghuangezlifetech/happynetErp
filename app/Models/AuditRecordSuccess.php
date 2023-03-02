<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditRecordSuccess extends AbstractModel {
    use HasFactory;

    protected $table = 'audit_record_successes';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function getDataByFilters($filters=[], $orderBy='created_at', $sort='DESC') {
        $query = $this->newModelQuery();

        if (!empty($filters)) {
            if (!empty($filters['audit_route_id'])) {
                $query->where('audit_route_id', $filters['audit_route_id']);
            }
        }

        $query->orderBy($orderBy, $sort);
        $results = $query->get();
        return $results;
    }

    public function getImgAttribute($img) {
        if ($img != '' && !is_null($img)) {
            return config('app.url').'/storage/'.$img;
        }
    }

    public function auditRecord() {
        return $this->belongsTo(AuditRecord::class, 'audit_record_id');
    }
}
