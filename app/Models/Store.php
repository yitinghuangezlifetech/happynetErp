<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends AbstractModel
{
    use SoftDeletes;

    protected $table = 'stores';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'group_id',
                'type' => 'select',
                'show_name' => '所屬群組',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Group',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'group_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'group_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['group_id.required'=>'請選擇所屬群組']
                ]),
            ],
            [
                'field' => 'store_type_id',
                'type' => 'select',
                'show_name' => '所屬分類',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\StoreType',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'store_industry_id',
                'type' => 'select',
                'show_name' => '所屬業別',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 2,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\StoreIndustry',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '商家名稱',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 3
            ],
            [
                'field' => 'food_no',
                'type' => 'text',
                'show_name' => '食品業者登錄字號',
                'join_search' => 1,
                'applicable_system' => json_encode([
                    '食安系統'
                ]),
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 4
            ],
            [
                'field' => 'year',
                'type' => 'text',
                'show_name' => '年度',
                'create' => 1,
                'edit' => 1,
                'sort' => 5
            ],
            [
                'field' => 'email',
                'type' => 'email',
                'show_name' => 'E-mail',
                'join_search' => 1,
                'required' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 6
            ],
            [
                'field' => 'manager',
                'type' => 'text',
                'show_name' => '負責人',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 7
            ],
            [
                'field' => 'sex',
                'type' => 'radio',
                'show_name' => '性別',
                'create' => 1,
                'edit' => 1,
                'sort' => 8,
                'options' => json_encode([
                    ['text'=>'男性', 'value'=>'m', 'default'=>1],
                    ['text'=>'女性', 'value'=>'f', 'default'=>0],
                ])
            ],
            [
                'field' => 'phone',
                'type' => 'text',
                'show_name' => '聯絡電話',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 9
            ],
            [
                'field' => 'address',
                'type' => 'text',
                'show_name' => '地址',
                'required' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 10
            ]
        ];
    }

    public function storeType()
    {
        return $this->belongsTo(StoreType::class, 'store_type_id');
    }

    public function storeIndustry()
    {
        return $this->belongsTo(StoreIndustry::class, 'store_industry_id');
    }

    public function photos()
    {
        return $this->hasMany(Image::class, 'reference_id');
    }

    public function getDataByFilters($filters=[], $orderBy='created_at', $sort='DESC')
    {
        $query = $this->newModelQuery();

        if (!empty($filters))
        {
            if (!empty($filters['name']))
            {
                $query->where('name', 'like', '%'.$filters['name'].'%');
            }
            if (!empty($filters['store_type_id']))
            {
                $query->where('store_type_id', $filters['store_type_id']);
            }
        }

        $query->orderBy($orderBy, $sort);
        $results = $query->paginate($filters['rows']??10);
        $results->appends($filters);

        return $results;
    }

    public function getAllDataByFilters($menuDetails, $filters=[], $orderBy='created_at', $sort='DESC')
    {
        $query = $this->newModelQuery();

        if (!empty($filters))
        {
            if (!empty($filters['name']))
            {
                $query->where('name', 'like', '%'.$filters['name'].'%');
            }
            if (!empty($filters['store_type_id']))
            {
                $query->where('store_type_id', $filters['store_type_id']);
            }
        }

        $query->orderBy($orderBy, $sort);
        $results = $query->get();
        return $results;
    }
}
