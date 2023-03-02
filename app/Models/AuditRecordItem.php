<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditRecordItem extends AbstractModel {
    use HasFactory;

    protected $table = 'audit_record_items';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function regulationItem() {
        return $this->belongsTo(RegulationItem::class, 'regulation_item_id');
    }
}
