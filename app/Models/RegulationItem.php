<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegulationItem extends AbstractModel
{
    use HasFactory;

    protected $table = 'regulation_items';
    protected $guarded = [];

    public function getFieldProperties() {}
}
