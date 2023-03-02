<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendingCheckItem extends AbstractModel {
    use HasFactory;

    protected $table = 'pending_check_items';
    protected $guarded = [];

    public function getFieldProperties() {}
}
