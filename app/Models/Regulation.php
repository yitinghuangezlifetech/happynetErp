<?php

namespace App\Models;

use Illuminate\Support\Facades\Schema;

class Regulation extends AbstractModel
{

    protected $table = 'regulations';
    protected $guarded = [];

    public function getFieldProperties() 
    {
        return [
            [
                'field' => 'system_type_id',
                'type' => 'select',
                'show_name' => '所屬系統類別',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\SystemType',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'system_type_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'system_type_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['system_type_id.required'=>'請選擇所屬系統類別']
                ]),
            ],
            [
                'field' => 'regulation_version_id',
                'type' => 'select',
                'show_name' => '所屬條文版本',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\RegulationVersion',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'regulation_version_id'=>'required',
                ]),
                'update_rule' => json_encode([
                    'regulation_version_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['regulation_version_id.required'=>'請選擇所屬條文版本']
                ]),
            ],
            [
                'field' => 'main_attribute_id',
                'type' => 'select',
                'show_name' => '所屬屬性',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 2,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\MainAttribute',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'sub_attribute_id',
                'type' => 'select',
                'show_name' => '所屬次屬性',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 3,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\SubAttribute',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'no',
                'type' => 'text',
                'show_name' => '條文項目',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 4
            ],
            [
                'field' => 'clause',
                'type' => 'text',
                'show_name' => '條文',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 5
            ],
            [
                'field' => 'items',
                'foreign_key' => 'id',
                'is_virtual_field' => 1,
                'type' => 'multiple_input',
                'show_name' => '數值項目',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 6,
                'has_js' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\RegulationItem',
                    'references_field' => 'regulation_id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'type',
                'type' => 'select',
                'show_name' => '類型',
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 7,
                'options' => json_encode([
                    ['text'=>'大型', 'value'=>1, 'default'=>1],
                    ['text'=>'小型', 'value'=>2, 'default'=>0],
                ])
            ],
            [
                'field' => 'law_source',
                'foreign_key' => 'sub_attribute_id',
                'is_virtual_field' => 1,
                'type' => 'text',
                'show_name' => '法源',
                'required' => 1,
                'browse' => 1,
                'sort' => 8,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\SubAttribute',
                    'references_field' => 'id',
                    'show_field' => 'law_source'
                ])
            ],
            [
                'field' => 'facts',
                'foreign_key' => 'id',
                'is_virtual_field' => 1,
                'type' => 'multiple_input',
                'show_name' => '所見事實項目',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 9,
                'has_js' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\RegulationFact',
                    'references_field' => 'regulation_id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'is_import',
                'type' => 'radio',
                'show_name' => '重點條文',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 10,
                'options' => json_encode([
                    ['text'=>'是', 'value'=>1, 'default'=>0],
                    ['text'=>'否', 'value'=>2, 'default'=>1],
                ])
            ],
            [
                'field' => 'is_main',
                'type' => 'radio',
                'show_name' => '主要條文',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 11,
                'options' => json_encode([
                    ['text'=>'是', 'value'=>1, 'default'=>0],
                    ['text'=>'否', 'value'=>2, 'default'=>1],
                ])
            ]
        ];
    }

    public function getListByFilters($menuDetails, $filters=[], $orderBy='no', $sort='ASC')
    {
        $query = $this->newModelQuery();

        if(Schema::hasColumn($this->table, 'deleted_at'))
        {
            $query->whereNull('deleted_at');
        }

        if ( count($filters) > 0)
        {
            foreach ($menuDetails as $detail)
            {
                if ( isset($filters[$detail->field]) && !empty($filters[$detail->field]) || !empty($filters['start_day']) && !empty($filters['end_day']) )
                {
                    switch ($detail->type)
                    {
                        case 'text':
                        case 'text_area':
                        case 'number':
                        case 'email':
                            $query->where($detail->field, 'like', '%'.$filters[$detail->field].'%');
                            break;
                        case 'date':
                        case 'date_time':
                            if (isset($filters['start_day']) && isset($filters['end_day']))
                            {
                                $query->where($detail->field, '<=', $filters['start_day'])
                                      ->where($detail->field, '>=', $filters['end_day']);
                            }    
                            break;
                        default:
                            $query->where($detail->field, $filters[$detail->field]);
                            break;
                    }
                }
            }
        }

        $query->orderBy($orderBy, $sort);
        $results = $query->paginate($filters['rows']??10);
        $results->appends($filters);

        return $results;
    }

    public function mainAttribute()
    {
        return $this->belongsTo(MainAttribute::class, 'main_attribute_id');
    }

    public function subAttribute()
    {
        return $this->belongsTo(SubAttribute::class, 'sub_attribute_id');
    }

    public function facts()
    {
        return $this->hasMany(RegulationFact::class, 'regulation_id');
    }

    public function items()
    {
        return $this->hasMany(RegulationItem::class, 'regulation_id');
    }

    public function failTypes()
    {
        return $this->hasManyThrough(AuditFailType::class, RegulationFail::class, 'regulation_id', 'id', 'id', 'audit_fail_type_id');
    }

    public function getAuditRecord($routeId, $storeId, $regulationId)
    {
        $log = app(AuditRecord::class)
            ->where('audit_route_id', $routeId)
            ->where('store_id', $storeId)
            ->where('regulation_id', $regulationId)
            ->first();
        return $log;
    }
}
