<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Counseling extends AbstractModel {
    use HasFactory;
    
    protected $table = 'counselings';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function getDataByRouteId($routeId) {
        return $this->where('audit_route_id', $routeId)->first();
    }

    public function updateCounseling($id, $data) {
        $this->where('id', $id)->update($data);
    }
}