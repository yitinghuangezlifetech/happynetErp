<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditRouteStoreImg extends AbstractModel {
    use HasFactory;

    protected $table = 'audit_route_store_imgs';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function getImgAttribute($img) {
        if ($img != '' && !is_null($img)) {
            return config('app.url').'/storage/'.$img;
        }
    }
}
