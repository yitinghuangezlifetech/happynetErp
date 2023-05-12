<?php

namespace App\Models;

class SpeedApplyContentItem extends AbstractModel
{
    protected $table = 'speed_apply_content_items';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function childrens()
    {
        return $this->hasMany(SpeedApplyItemChild::class, 'speed_apply_item_id');
    }
}
