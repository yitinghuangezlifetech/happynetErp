<?php

namespace App\Models;

class Contract extends AbstractModel
{
    protected $table = 'contracts';
    protected $guarded = [];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'contract_no',
                'type' => 'text',
                'show_name' => '合約編號',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'create_rule' => json_encode([
                    'contract_no'=>'required'
                ]),
                'update_rule' => json_encode([
                    'contract_no'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['contract_no.required'=>'合約編號請勿空白']
                ]),
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '合約名稱',
                'use_edit_link'=>1,
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
                    ['name.required'=>'合約名稱請勿空白']
                ]),
            ],
            [
                'field' => 'project_id',
                'type' => 'select',
                'show_name' => '適用專案',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 3,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Project',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'project_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'project_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['project_id.required'=>'請選擇適用專案']
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
                'sort' => 4,
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
                    ['identity_id.required'=>'請選擇適用對象']
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
                'sort' => 5,
                'options' => json_encode([
                    ['text'=>'一般', 'value'=>1, 'default'=>0],
                    ['text'=>'範本', 'value'=>2, 'default'=>0],
                ])
            ],
            [
                'field' => 'version_no',
                'type' => 'text',
                'show_name' => '版本號',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 6,
                'create_rule' => json_encode([
                    'version_no'=>'required'
                ]),
                'update_rule' => json_encode([
                    'version_no'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['version_no.required'=>'版本號請勿空白']
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
                'sort' => 7,
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
                'field' => 'pay_way',
                'type' => 'select',
                'show_name' => '付款方式',
                'join_search' => 1,
                'required' => 1,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 15,
                'options' => json_encode([
                    ['text'=>'先付', 'value'=>1, 'default'=>0],
                    ['text'=>'後付', 'value'=>2, 'default'=>0]
                ])
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
                'sort' => 19,
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
                'sort' => 20,
            ],
            [
                'field' => 'status',
                'type' => 'radio',
                'show_name' => '狀態',
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'sort' => 29,
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
                'sort' => 30
            ],
        ];
    }

    public function getProductsByType($typeId)
    {
        return app(Product::class)
            ->where('status', 1)
            ->where('product_type_id', $typeId)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function logs()
    {
        return $this->hasMany(ContractProductLog::class, 'contract_id');
    }
}
