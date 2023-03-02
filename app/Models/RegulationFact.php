<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegulationFact extends AbstractModel {
    use HasFactory;

    protected $table = 'regulation_facts';
    protected $guarded = [];

    public function getFieldProperties() {}
}
