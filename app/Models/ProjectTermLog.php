<?php

namespace App\Models;

class ProjectTermLog extends AbstractModel
{
    protected $table = 'project_term_logs';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function term()
    {
        return $this->belongsTo(Term::class, 'term_id');
    }
}
