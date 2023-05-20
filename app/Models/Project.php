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
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
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
                'field' => 'code',
                'type' => 'text',
                'show_name' => '專案代碼',
                'use_edit_link'=>1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'create_rule' => json_encode([
                    'code'=>'required'
                ]),
                'update_rule' => json_encode([
                    'code'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['code.required'=>'專案代碼請勿空白']
                ]),
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '專案名稱',
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
                    ['name.required'=>'專案名稱請勿空白']
                ]),
            ],
            [
                'field' => 'empty',
                'create' => 1,
                'edit' => 1,
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
                'field' => 'apply_type_id',
                'type' => 'select',
                'show_name' => '申請類別',
                'use_edit_link'=>2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 2,
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
                'field' => 'close_period_id',
                'type' => 'select',
                'show_name' => '結算區間',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
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
                'field' => 'pay_way',
                'type' => 'select',
                'show_name' => '付款方式',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'options' => json_encode([
                    ['text'=>'先付', 'value'=>1, 'default'=>0],
                    ['text'=>'後付', 'value'=>2, 'default'=>0]
                ])
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
                'browse' => 1,
            ],
            [
                'field' => 'invalid_date',
                'type' => 'date_time',
                'show_name' => '停用日期',
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

    public function logs()
    {
        return $this->hasMany(ProjectProduct::class, 'project_id');
    }
}
