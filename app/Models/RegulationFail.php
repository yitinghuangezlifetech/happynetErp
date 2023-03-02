<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegulationFail extends AbstractModel {
    use HasFactory;

    protected $table = 'regulation_fails';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function regulation() {
        return $this->belongsTo(Regulation::class, 'regulation_id');
    }
}
