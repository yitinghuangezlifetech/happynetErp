<?php

namespace App\Models;

class Menu extends AbstractModel
{
    protected $table = 'menus';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'menu_name',
                'type' => 'text',
                'show_name' => '目錄項目名稱',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'create_rule' => json_encode([
                    'menu_name'=>'required | unique:menus'
                ]),
                'update_rule' => json_encode([
                    'menu_name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['menu_name.required'=>'目錄項目名稱請勿空白'],
                    ['menu_name.unique'=>'目錄項目名稱重覆'],
                ]),
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '簡化項目名稱',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'簡化項目名稱請勿空白']
                ]),
            ],
            [
                'field' => 'slug',
                'type' => 'text',
                'show_name' => 'Slug',
                'create' => 1,
                'edit' => 1,
                'sort' => 2,
                'create_rule' => json_encode([
                    'slug'=>'required | unique:menus'
                ]),
                'update_rule' => json_encode([
                    'slug'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['slug.required'=>'Slug請勿空白'],
                    ['slug.unique'=>'Slug重覆'],
                ]),
            ],
            [
                'field' => 'target',
                'type' => 'select',
                'show_name' => '開啟方式',
                'create' => 1,
                'edit' => 1,
                'sort' => 3,
                'options' => json_encode([
                    ['text'=>'在目前視窗中開啟', 'value'=>'_self'],
                    ['text'=>'另外開啟視窗', 'value'=>'_blank'],
                ])
            ],
            [
                'field' => 'icon_class',
                'type' => 'text',
                'show_name' => 'Icon Class',
                'create' => 1,
                'edit' => 1,
                'sort' => 4
            ],
            [
                'field' => 'model',
                'type' => 'text',
                'show_name' => 'Model',
                'create' => 1,
                'edit' => 1,
                'sort' => 5
            ],
            [
                'field' => 'controller',
                'type' => 'text',
                'show_name' => 'Controller',
                'create' => 1,
                'edit' => 1,
                'sort' => 6
            ],
            [
                'field' => 'seo_enable',
                'type' => 'radio',
                'show_name' => '啟用SEO功能',
                'create' => 1,
                'edit' => 1,
                'sort' => 7,
                'options' => json_encode([
                    ['text'=>'是', 'value'=>1, 'default'=>2],
                    ['text'=>'否', 'value'=>2, 'default'=>1],
                ])
            ],
            [
                'field' => 'sortable_enable',
                'type' => 'radio',
                'show_name' => '啟用排序功能',
                'create' => 1,
                'edit' => 1,
                'sort' => 8,
                'options' => json_encode([
                    ['text'=>'是', 'value'=>1, 'default'=>2],
                    ['text'=>'否', 'value'=>2, 'default'=>1],
                ])
            ],
            [
                'field' => 'export_data',
                'type' => 'radio',
                'show_name' => '啟用資料匯出功能',
                'create' => 1,
                'edit' => 1,
                'sort' => 8,
                'options' => json_encode([
                    ['text'=>'是', 'value'=>1, 'default'=>2],
                    ['text'=>'否', 'value'=>2, 'default'=>1],
                ])
            ],
            [
                'field' => 'search_component',
                'type' => 'radio',
                'show_name' => '啟用搜尋功能',
                'create' => 1,
                'edit' => 1,
                'sort' => 9,
                'options' => json_encode([
                    ['text'=>'是', 'value'=>1, 'default'=>2],
                    ['text'=>'否', 'value'=>2, 'default'=>1],
                ])
            ],
            [
                'field' => 'no_show',
                'type' => 'radio',
                'show_name' => '是否不顥示於目錄',
                'create' => 1,
                'edit' => 1,
                'sort' => 10,
                'options' => json_encode([
                    ['text'=>'是', 'value'=>1, 'default'=>2],
                    ['text'=>'否', 'value'=>2, 'default'=>1],
                ])
            ]
        ];
    }

    public function menuDetails() 
    {
        return $this->hasMany(MenuDetail::class, 'menu_id');
    }

    public function menuRequiredDetails() 
    {
        return $this->hasMany(MenuDetail::class, 'menu_id')->where('required', 1);
    }

    public function menuBrowseDetails()
    {
        return $this->hasMany(MenuDetail::class, 'menu_id')->where('browse', 1)->orderBy('sort', 'ASC');
    }

    public function menuCreateDetails()
    {
        return $this->hasMany(MenuDetail::class, 'menu_id')->where('create', 1)->orderBy('sort', 'ASC');
    }

    public function menuEditDetails()
    {
        return $this->hasMany(MenuDetail::class, 'menu_id')->where('edit', 1)->orderBy('sort', 'ASC');
    }

    public function menuSearchDetails()
    {
        return $this->hasMany(MenuDetail::class, 'menu_id')->where('join_search', 1)->orderBy('sort', 'ASC');
    }

    public function getChilds()
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->where('no_show', 2)
            ->orderBy('sort', 'ASC');
    }

    public function getAllChilds()
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->orderBy('sort', 'ASC');
    }

    public function getParent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'menu_id');
    }
}
