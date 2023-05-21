<?php

namespace App\Models;

class Term extends AbstractModel
{
    protected $table = 'terms';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'term_type_id',
                'type' => 'select',
                'show_name' => '條文類型',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\FuncType',
                    'references_field' => 'id',
                    'type_code' => 'term_types',
                    'show_field' => 'type_name'
                ]),
                'create_rule' => json_encode([
                    'term_type_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'term_type_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['term_type_id.required'=>'請選擇條文類型']
                ]),
            ],
            [
                'field' => 'sales_type_id',
                'type' => 'select',
                'show_name' => '適用銷售模式',
                'join_search' => 1,
                'required' => 2,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
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
                    ['sales_type_id.required'=>'請選擇適用銷售模式']
                ]),
            ],
            [
                'field' => 'product_type_id',
                'type' => 'select',
                'show_name' => '適用商品類型',
                'join_search' => 1,
                'required' => 2,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
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
                    ['product_type_id.required'=>'請選擇適用商品類型']
                ]),
            ],
            [
                'field' => 'title',
                'type' => 'text',
                'show_name' => '條文標題',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'create_rule' => json_encode([
                    'title'=>'required'
                ]),
                'update_rule' => json_encode([
                    'title'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['title.required'=>'條文標題請勿空白']
                ]),
            ],
            [
                'field' => 'describe',
                'type' => 'text',
                'show_name' => '標題說明',
                'use_edit_link'=>1,
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'content',
                'type' => 'ckeditor',
                'show_name' => '條文內容',
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'has_js' => 1,
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
            ],
        ];
    }

    public function getTermsByFilters($filters=[])
    {
        $query = $this->newModelQuery();
        
        if (!empty($filters))
        {
            if (!empty($filters['term_type_id']))
            {
                $query->where('term_type_id', $filters['term_type_id']);
            }
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
