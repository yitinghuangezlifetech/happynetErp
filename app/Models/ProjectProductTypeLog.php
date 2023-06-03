<?php

namespace App\Models;

class ProjectProductTypeLog extends AbstractModel
{
    protected $table = 'project_product_type_logs';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function productType()
    {
        return $this->belongsTo(FuncType::class, 'product_type_id', 'id');
    }

    public function productLogs()
    {
        return $this->hasMany(ProjectProduct::class, 'log_id');
    }
}
