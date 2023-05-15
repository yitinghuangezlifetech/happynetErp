<?php

namespace App\Models;

class ProjectRegulationLog extends AbstractModel
{
    protected $table = 'project_regulation_logs';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
