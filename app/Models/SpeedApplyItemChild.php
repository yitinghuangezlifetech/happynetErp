<?php

namespace App\Models;

class SpeedApplyItemChild extends AbstractModel
{
    protected $table = 'speed_apply_item_children';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function items()
    {
        return $this->hasMany(SpeedApplyChildrenItem::class, 'speed_apply_item_children_id');
    }
}
