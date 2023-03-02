<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainAttribute extends AbstractModel {
    use SoftDeletes;

    protected $table = 'main_attributes';
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
                'field' => 'audit_type_id',
                'type' => 'select',
                'show_name' => '所屬稽核類別',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\AuditType',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'audit_type_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'audit_type_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['audit_type_id.required'=>'請選擇所屬稽核類別']
                ]),
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '屬性名稱',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 2
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 3
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
        $results = $query->paginate($filters['rows']??10);
        $results->appends($filters);

        return $results;
    }

    public function subAttributes() {
        return $this->hasMany(SubAttribute::class, 'main_attribute_id')->orderBy('sort', 'ASC');
    }

    public function getSubAttributesByType($type) {
        return $this->hasMany(SubAttribute::class, 'main_attribute_id')
            ->where('type', $type)
            ->orderBy('sort', 'ASC')
            ->get();
    }

    public function regulations() {
        return $this->hasMany(Regulation::class, 'main_attribute_id')->orderBy('no', 'ASC');
    }

    public function getRegulationsByType($type) {
        return $this->hasMany(Regulation::class, 'main_attribute_id')
            ->where('type', $type)
            ->orderBy('no', 'ASC')
            ->get();
    }
}
