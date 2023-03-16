<?php

namespace App\Models;
use Illuminate\Support\Facades\Schema;

class FuncType extends AbstractModel
{
    protected $table = 'func_type';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '類別名稱',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'類別名稱請勿空白']
                ]),
            ],
            [
                'field' => 'type_code',
                'type' => 'text',
                'show_name' => '類別代碼',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'類別名稱請勿空白']
                ]),
            ],
            [
                'field' => 'type_value',
                'type' => 'text',
                'show_name' => '類別代碼值',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'類別名稱請勿空白']
                ]),
            ],
            [
                'field' => 'type_name',
                'type' => 'text',
                'show_name' => '類別名稱',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'類別名稱請勿空白']
                ]),
            ],
            [
                'field' => 'description',
                'type' => 'text',
                'show_name' => '描述',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'類別名稱請勿空白']
                ]),
            ],
            [
                'field' => 'status',
                'type' => 'text',
                'show_name' => '狀態',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'類別名稱請勿空白']
                ]),
            ],
            [
                'field' => 'dsp',
                'type' => 'text',
                'show_name' => '顯示',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'類別名稱請勿空白']
                ]),
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 1
            ],
        ];
    }

    public function getListByFilters($menuDetails, $filters=[], $orderBy='created_at', $sort='DESC')
    {
        $query = $this->newModelQuery();

        if(Schema::hasColumn($this->table, 'deleted_at'))
        {
            $query->whereNull('deleted_at');
        }

        if ( count($filters) > 0)
        {
            if(!empty($filters['slug'])) {
                $query->where('type_code', $filters['slug']);
            }
        }

        $query->orderBy($orderBy, $sort);
        $results = $query->paginate($filters['rows']??10);
        $results->appends($filters);

        return $results;
    }


    public function getChilds()
    {
        return $this->hasMany(FuncType::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(FuncType::class, 'parent_id');
    }

    public function systemTypes()
    {
        return $this->hasManyThrough(SystemType::class, GroupSystemTypeLog::class, 'group_id', 'id', 'id', 'system_type_id');
    }
}