<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProjectRegulation extends AbstractModel
{
    protected $table = 'project_regulations';
    protected $guarded = [];

    public function getFieldProperties() 
    {
        return [
            [
                'field' => 'sales_type_id',
                'type' => 'select',
                'show_name' => '銷售模式',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 2,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\FuncType',
                    'references_field' => 'id',
                    'type_code' => 'sales_types',
                    'show_field' => 'type_name'
                ]),
                'create_rule' => json_encode([
                    'sales_type_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'sales_type_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['sales_type_id.required'=>'請選擇銷售模式']
                ]),
            ],
            [
                'field' => 'product_type_id',
                'type' => 'select',
                'show_name' => '商品類別',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 4,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\FuncType',
                    'references_field' => 'id',
                    'type_code' => 'product_types',
                    'show_field' => 'type_name'
                ]),
                'create_rule' => json_encode([
                    'product_type_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'product_type_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['product_type_id.required'=>'請選擇商品類別']
                ]),
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '條文名稱',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 5
            ],
            [
                'field' => 'content',
                'type' => 'ckeditor',
                'show_name' => '條文內容',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 6
            ],
            [
                'field' => 'status',
                'type' => 'radio',
                'show_name' => '狀態',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 2,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'sort' => 7,
                'options' => json_encode([
                    ['text'=>'啟用', 'value'=>1, 'default'=>1],
                    ['text'=>'停用', 'value'=>2, 'default'=>0],
                ])
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 8
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料停用日期',
                'browse' => 1,
                'sort' => 9
            ],
        ];
    }

    public function getProjects()
    {
        return app(Project::class)
            ->where('status', 1)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function logs()
    {
        return $this->hasMany(ProjectRegulationLog::class, 'project_regulation_id');
    }

    public function getDataByFilters($filters=[])
    {
        $query = $this->newModelQuery();

        if(Schema::hasColumn($this->table, 'deleted_at'))
        {
            $query->whereNull('deleted_at');
        }

        if (!empty($filters))
        {
            if (!empty($filters['sales_type_id']))
            {
                $query->where('sales_type_id', $filters['sales_type_id']);
            }
            if (!empty($filters['product_type_id']))
            {
                $query->where('product_type_id', $filters['product_type_id']);
            }
        }

        $query->orderBy('created_at', 'DESC');
        $results = $query->get();

        return $results;
    }
}
