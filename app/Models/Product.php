<?php

namespace App\Models;

class Product extends AbstractModel
{
    protected $table = 'products';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'product_category',
                'type' => 'text',
                'show_name' => '商品類型',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'create_rule' => json_encode([
                    'product_category'=>'required'
                ]),
                'update_rule' => json_encode([
                    'product_category'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['product_category.required'=>'商品類型請勿空白']
                ]),
            ],
            [
                'field' => 'product_type_id',
                'type' => 'select',
                'show_name' => '品項類別',
                'use_edit_link'=>2,
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
                    ['product_type_id.required'=>'請選擇品項類別']
                ]),
            ],
            [
                'field' => 'sales_type_id',
                'type' => 'select',
                'show_name' => '銷售模式',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 3,
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
                'field' => 'name',
                'type' => 'text',
                'show_name' => '產品名稱',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 4,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'產品名稱請勿空白']
                ]),
            ],
            [
                'field' => 'model_no',
                'type' => 'text',
                'show_name' => '產品型號',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 5,
                'create_rule' => json_encode([
                    'model_no'=>'required'
                ]),
                'update_rule' => json_encode([
                    'model_no'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['model_no.required'=>'產品型號請勿空白']
                ]),
            ],
            [
                'field' => 'spec',
                'type' => 'text',
                'show_name' => '產品規格',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 1,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 6,
                'create_rule' => json_encode([
                    'spec'=>'required'
                ]),
                'update_rule' => json_encode([
                    'spec'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['spec.required'=>'產品規格請勿空白']
                ]),
            ],
            [
                'field' => 'charge_type',
                'type' => 'select',
                'show_name' => '收費方式',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 7,
                'options' => json_encode([
                    ['text'=>'月租', 'value'=>1, 'default'=>0],
                    ['text'=>'買斷', 'value'=>2, 'default'=>0],
                ])
            ],
            [
                'field' => 'price',
                'type' => 'text',
                'show_name' => '產品原價',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 8,
                'create_rule' => json_encode([
                    'price'=>'required'
                ]),
                'update_rule' => json_encode([
                    'price'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['price.required'=>'產品原價請勿空白']
                ]),
            ],
            [
                'field' => 'rate_no',
                'type' => 'text',
                'show_name' => '費率編號',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 9,
            ],
            [
                'field' => 'discount_rate',
                'type' => 'text',
                'show_name' => '折後費率',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 10,
            ],
            [
                'field' => 'status',
                'type' => 'radio',
                'show_name' => '狀態',
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'sort' => 11,
                'options' => json_encode([
                    ['text'=>'啟用', 'value'=>1, 'default'=>0],
                    ['text'=>'停用', 'value'=>2, 'default'=>1],
                ])
            ],
            [
                'field' => 'create_user_id',
                'type' => 'select',
                'show_name' => '建立人員',
                'join_search' => 2,
                'required' => 2,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'sort' => 12,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 13
            ],
            [
                'field' => 'update_user_id',
                'type' => 'select',
                'show_name' => '修改人員',
                'join_search' => 2,
                'required' => 2,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'sort' => 14,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'updated_at',
                'type' => 'date_time',
                'show_name' => '資料修改日期',
                'browse' => 1,
                'sort' => 15
            ],
        ];
    }
}