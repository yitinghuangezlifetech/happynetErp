<?php

namespace App\Models;

class Permission extends AbstractModel
{
    protected $table = 'permissions';
    protected $guarded = [];

    public function getFieldProperties(){}

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
