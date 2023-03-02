<?php

namespace App\Models;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubAttribute extends AbstractModel {
    use SoftDeletes;

    protected $table = 'sub_attributes';
    protected $guarded = [];

    public function getFieldProperties() {
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
                'field' => 'main_attribute_id',
                'type' => 'select',
                'show_name' => '所屬屬性',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\MainAttribute',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'type',
                'type' => 'select',
                'show_name' => '類型',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 2,
                'options' => json_encode([
                    ['text'=>'大型', 'value'=>1],
                    ['text'=>'小型', 'value'=>2],
                ])
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '次屬性名稱',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 3
            ],
            [
                'field' => 'law_source',
                'type' => 'text',
                'show_name' => '法源',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 4
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 5
            ],
        ];
    }

    public function getListByFilters($menuDetails, $filters=[], $orderBy='sort', $sort='ASC') {
        $query = $this->newModelQuery();

        if(Schema::hasColumn($this->table, 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        if ( count($filters) > 0) {
            foreach ($menuDetails as $detail) {
                if ( isset($filters[$detail->field]) && !empty($filters[$detail->field]) || !empty($filters['start_day']) && !empty($filters['end_day']) ) {
                    switch ($detail->type) {
                        case 'text':
                        case 'text_area':
                        case 'number':
                        case 'email':
                            $query->where($detail->field, 'like', '%'.$filters[$detail->field].'%');
                            break;
                        case 'date':
                        case 'date_time':
                            if (isset($filters['start_day']) && isset($filters['end_day'])) {
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
        $results = $query->paginate($filters['rows']??30);
        $results->appends($filters);

        return $results;
    }

    public function getRegulationByStoreType($type=null) {
        if (!empty($type)) {
            return app(Regulation::class)->where('sub_attribute_id', $this->id)
                ->where('type', $type)
                ->orderBy('no', 'ASC')
                ->get();
        }
        return app(Regulation::class)->where('sub_attribute_id', $this->id)->orderBy('no', 'ASC')->get();
    }
}
