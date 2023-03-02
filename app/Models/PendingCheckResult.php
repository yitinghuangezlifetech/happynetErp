<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendingCheckResult extends AbstractModel {
    use HasFactory;

    protected $table = 'pending_check_results';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function getImgAttribute($img) {
        if ($img != '' && !is_null($img)) {
            return config('app.url').'/storage/'.$img;
        }
    }
}
