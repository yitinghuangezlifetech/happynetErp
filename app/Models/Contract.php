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
                'field' => 'version_no',
                'type' => 'text',
                'show_name' => '版本號',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
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
                'field' => 'name',
                'type' => 'text',
                'show_name' => '合約名稱',
                'use_edit_link'=>1,
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
                    ['name.required'=>'合約名稱請勿空白']
                ]),
            ],
            [
                'field' => 'plan_type_id',
                'type' => 'select',
                'show_name' => '方案類別',
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
                    'type_code' => 'plan_types',
                    'show_field' => 'type_name'
                ]),
                'create_rule' => json_encode([
                    'plan_type_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'plan_type_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['plan_type_id.required'=>'請選擇方案類別']
                ]),
            ],
            [
                'field' => 'apply_type_id',
                'type' => 'select',
                'show_name' => '申請類別',
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
                'field' => 'identity_id',
                'type' => 'select',
                'show_name' => '對象',
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
                'field' => 'status',
                'type' => 'radio',
                'show_name' => '狀態',
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'options' => json_encode([
                    ['text'=>'啟用', 'value'=>1, 'default'=>0],
                    ['text'=>'停用', 'value'=>2, 'default'=>1],
                ])
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1
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

    public function products()
    {
        return $this->hasMany(ContractProductLog::class, 'contract_id');
    }

    public function terms()
    {
        return $this->hasMany(ContractTermLog::class, 'contract_id')->orderBy('sort', 'ASC');
    }
}
