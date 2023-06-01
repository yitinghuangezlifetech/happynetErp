<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Product extends AbstractModel
{
    protected $table = 'products';
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
                'show_name' => '商品類型',
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
                    ['product_type_id.required'=>'商品類型請勿空白']
                ]),
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '商品名稱',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'商品名稱請勿空白']
                ]),
            ],
            [
                'field' => 'empty',
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'fee_rate_id',
                'type' => 'select',
                'show_name' => '費率模組',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\FeeRate',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'price',
                'type' => 'text',
                'show_name' => '商品價格',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 2,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'rent_month',
                'type' => 'text',
                'show_name' => '月租費用',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'deposit_amount',
                'type' => 'text',
                'show_name' => '保證金',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
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
            ],
        ];
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

    public function getProductsByTypeId($typeId)
    {
        $query = $this->newModelQuery();
        $query->where('product_type_id', $typeId)
              ->orderBy('created_at', 'DESC');
        $results = $query->get();

        return $results;
    }

    public function feeRate()
    {
        return $this->belongsTo(FeeRate::class, 'fee_rate_id');
    }
}
