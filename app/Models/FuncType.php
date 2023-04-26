<?php

namespace App\Models;
use Illuminate\Support\Facades\Schema;

class FuncType extends AbstractModel
{
    protected $table = 'func_types';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
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
                'sort' => 1,
                'create_rule' => json_encode([
                    'type_code'=>'required'
                ]),
                'update_rule' => json_encode([
                    'type_code'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['type_name.required'=>'類別名稱請勿空白']
                ]),
            ],
            [
                'field' => 'type_value',
                'type' => 'text',
                'show_name' => '類別代碼值',
                'use_edit_link'=>1,
                'join_search' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 2,
                'create_rule' => json_encode([
                    'type_code'=>'required'
                ]),
                'update_rule' => json_encode([
                    'type_code'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['type_code.required'=>'類別代碼請勿空白']
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
                'sort' => 3,
                'create_rule' => json_encode([
                    'type_name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'type_name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['type_name.required'=>'類別代碼值請勿空白']
                ]),
            ],
            [
                'field' => 'description',
                'type' => 'text',
                'show_name' => '描述',
                'use_edit_link'=>1,
                'join_search' => 2,
                'required' => 2,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 4,
            ],
            [
                'field' => 'status',
                'type' => 'radio',
                'show_name' => '狀態',
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'sort' => 5,
                'options' => json_encode([
                    ['text'=>'啟用', 'value'=>1, 'default'=>0],
                    ['text'=>'停用', 'value'=>2, 'default'=>1],
                ])
            ],
            [
                'field' => 'dsp',
                'type' => 'radio',
                'show_name' => '顯示',
                'required' => 1,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'sort' => 6,
                'options' => json_encode([
                    ['text'=>'顯示', 'value'=>1, 'default'=>0],
                    ['text'=>'不顯示', 'value'=>2, 'default'=>1],
                ])
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 7
            ],
        ];
    }

    public function getChilds($filters=null)
    {
        $query = $this->hasMany(FuncType::class, 'parent_id');

        if (!is_null($filters))
        {
            if (isset($filters['type_code'])) 
            {
                $query->where('type_code', 'like', '%'.$filters['type_code'].'%');
            }
            if (isset($filters['type_value'])) 
            {
                $query->where('type_value', 'like', '%'.$filters['type_value'].'%');
            }
            if (isset($filters['type_name'])) 
            {
                $query->where('type_name', 'like', '%'.$filters['type_name'].'%');
            }

            return $query->orderBy('created_at', 'DESC');
        }
        else
        {
            return $this->hasMany(FuncType::class, 'parent_id')
                ->orderBy('created_at', 'DESC');
        }
    }

    public function getChildNoFilter()
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

    public function getDataByTypeCode($typeCode)
    {
        return $this->where('type_code', $typeCode)->first();
    }

    public function getChildsByTypeCode($typeCode)
    {
        $data = $this->where('type_code', $typeCode)->first();

        if ($data)
        {
            return app(FuncType::class)
                ->where('parent_id', $data->id)
                ->orderBy('created_at', 'DESC')
                ->get();
        }

        return (new Collection([]))->get(); 
    }
}
