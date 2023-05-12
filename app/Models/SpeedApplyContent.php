<?php

namespace App\Models;

class SpeedApplyContent extends AbstractModel
{
    protected $table = 'speed_apply_contents';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function seedApplySet()
    {
        return $this->belongsTo(SpeedApplySet::class, 'speed_apply_set_id');
    }

    public function items()
    {
        return $this->hasMany(SpeedApplyContentItem::class, 'speed_apply_content_id');
    }
}
