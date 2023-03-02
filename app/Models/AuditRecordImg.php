<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditRecordImg extends AbstractModel {
    use HasFactory;

    protected $table = 'audit_record_items';
    protected $guarded = [];

    public function getFieldProperties() {}
}
