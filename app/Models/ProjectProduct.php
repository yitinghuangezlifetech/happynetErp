<?php

namespace App\Models;

class ProjectProduct extends AbstractModel
{
    protected $table = 'project_products';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
