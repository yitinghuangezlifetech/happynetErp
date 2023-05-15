<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Project extends AbstractModel
{
    protected $table = 'projects';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'project_no',
                'type' => 'text',
                'show_name' => '專案編號',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'create_rule' => json_encode([
                    'project_no'=>'required'
                ]),
                'update_rule' => json_encode([
                    'project_no'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['project_no.required'=>'專案編號請勿空白']
                ]),
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '專案名稱',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 2,
                'create_rule' => json_encode([
                    'name'=>'required'
                ]),
                'update_rule' => json_encode([
                    'name'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required'=>'專案名稱請勿空白']
                ]),
            ],
            [
                'field' => 'identity_id',
                'type' => 'select',
                'show_name' => '對象',
                'use_edit_link'=>2,
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
                    'type_code' => 'identity_types',
                    'show_field' => 'type_name'
                ]),
                'create_rule' => json_encode([
                    'identity_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'identity_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['identity_id.required'=>'請選擇對象']
                ]),
            ],
            [
                'field' => 'type',
                'type' => 'select',
                'show_name' => '類型',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 4,
                'options' => json_encode([
                    ['text'=>'一般', 'value'=>1, 'default'=>0],
                    ['text'=>'範本', 'value'=>2, 'default'=>0],
                ])
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
                'sort' => 5,
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
                'field' => 'apply_type_id',
                'type' => 'select',
                'show_name' => '申請類別',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 6,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\FuncType',
                    'references_field' => 'id',
                    'type_code' => 'apply_types',
                    'show_field' => 'type_name'
                ]),
                'create_rule' => json_encode([
                    'apply_type_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'apply_type_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['apply_type_id.required'=>'請選擇申請類別']
                ]),
            ],
            [
                'field' => 'close_period_id',
                'type' => 'select',
                'show_name' => '結算區間',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 7,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\ClosePeriod',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'close_period_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'close_period_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['close_period_id.required'=>'請選擇結算區間']
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
                'sort' => 8,
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
                'field' => 'effective_day',
                'type' => 'date',
                'show_name' => '生效日期',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 9,
                'create_rule' => json_encode([
                    'effective_day'=>'required'
                ]),
                'update_rule' => json_encode([
                    'effective_day'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['effective_day.required'=>'生效日期請勿空白']
                ]),
            ],
            [
                'field' => 'expiration_day',
                'type' => 'date',
                'show_name' => '截止日期',
                'use_edit_link'=>2,
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 10,
                'create_rule' => json_encode([
                    'expiration_day'=>'required'
                ]),
                'update_rule' => json_encode([
                    'expiration_day'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['expiration_day.required'=>'截止日期請勿空白']
                ]),
            ],
            [
                'field' => 'pay_way',
                'type' => 'select',
                'show_name' => '付款方式',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 11,
                'options' => json_encode([
                    ['text'=>'先付', 'value'=>1, 'default'=>0],
                    ['text'=>'後付', 'value'=>2, 'default'=>0]
                ])
            ],
            [
                'field' => 'discount_set',
                'type' => 'text',
                'show_name' => '折抵設定',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 12,
            ],
            [
                'field' => 'call_rate',
                'type' => 'text',
                'show_name' => '通話費率',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 13,
            ],
            [
                'field' => 'promotion_type',
                'type' => 'select',
                'show_name' => '優惠類型',
                'join_search' => 1,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 14,
                'options' => json_encode([
                    ['text'=>'金額', 'value'=>1, 'default'=>0],
                    ['text'=>'百分比', 'value'=>2, 'default'=>0]
                ])
            ],
            [
                'field' => 'discount',
                'type' => 'text',
                'show_name' => '優惠(元/%)',
                'join_search' => 2,
                'required' => 2,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 15,
            ],
            [
                'field' => 'status',
                'type' => 'radio',
                'show_name' => '狀態',
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'sort' => 18,
                'options' => json_encode([
                    ['text'=>'啟用', 'value'=>1, 'default'=>0],
                    ['text'=>'停用', 'value'=>2, 'default'=>1],
                ])
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 19
            ],
            [
                'field' => 'invalid_date',
                'type' => 'date_time',
                'show_name' => '停用日期',
                'browse' => 1,
                'sort' => 20
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
}
