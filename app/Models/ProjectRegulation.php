<?php

namespace App\Models;

class ProjectRegulation extends AbstractModel
{
    protected $table = 'project_regulations';
    protected $guarded = [];

    public function getFieldProperties() 
    {
        return [
            [
                'field' => 'close_period_id',
                'type' => 'select',
                'show_name' => '結算區間',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
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
                    'type_code' => 'sales_type',
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
                'sort' => 3,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\FuncType',
                    'references_field' => 'id',
                    'type_code' => 'apply_type',
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
                'field' => 'service_type_id',
                'type' => 'select',
                'show_name' => '服務類別',
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
                    'type_code' => 'service_type',
                    'show_field' => 'type_name'
                ]),
                'create_rule' => json_encode([
                    'service_type_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'service_type_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['service_type_id.required'=>'請選擇服務類別']
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
}
