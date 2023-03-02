<?php

namespace App\Models;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditRoute extends AbstractModel {
    use HasFactory;

    protected $table = 'audit_routes';
    protected $guarded = [];

    public function getFieldProperties() {
        return [
            [
                'field' => 'system_type_id',
                'type' => 'component',
                'show_name' => '所屬系統類別',
                'show_hidden_field'=>1,
                'use_component' => 1,
                'component_name' => 'SystemType',
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
                'relationship_method'=>'systemTypes',
            ],
            [
                'field' => 'main_user_id',
                'type' => 'select',
                'show_name' => '主稽核人員',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'sub_user_id',
                'type' => 'select',
                'show_name' => '副稽核人員',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 2,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'store_id',
                'type' => 'select',
                'show_name' => '稽核店家',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 3,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Store',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'audit_day',
                'type' => 'date',
                'show_name' => '稽核日期',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 4
            ],
            [
                'field' => 'enable_routine',
                'type' => 'radio',
                'show_name' => '啟用例行公事',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 5,
                'options' => json_encode([
                    ['text'=>'是', 'value'=>1, 'default'=>0],
                    ['text'=>'否', 'value'=>2, 'default'=>1],
                ])
            ],
            [
                'field' => 'routine_option',
                'type' => 'select',
                'show_name' => '例行定義',
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 6,
                'options' => json_encode([
                    ['text'=>'每週', 'value'=>1, 'default'=>0],
                    ['text'=>'每月', 'value'=>2, 'default'=>0],
                    ['text'=>'每年', 'value'=>3, 'default'=>0],
                ])
            ],
            [
                'field' => 'audit_status',
                'type' => 'select',
                'show_name' => '稽核狀態',
                'required' => 1,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'sort' => 7,
                'options' => json_encode([
                    ['text'=>'未開始', 'value'=>0, 'default'=>0],
                    ['text'=>'輔導', 'value'=>1, 'default'=>1],
                    ['text'=>'評核', 'value'=>2, 'default'=>0],
                    ['text'=>'追評', 'value'=>3, 'default'=>0]
                ])
            ],
            [
                'field' => 'status',
                'type' => 'select',
                'show_name' => '狀態',
                'required' => 1,
                'browse' => 1,
                'create' => 2,
                'edit' => 2,
                'sort' => 8,
                'options' => json_encode([
                    ['text'=>'未開始', 'value'=>0, 'default'=>1],
                    ['text'=>'開始', 'value'=>1, 'default'=>0],
                    ['text'=>'稽核中', 'value'=>2, 'default'=>0],
                    ['text'=>'開始上傳', 'value'=>3, 'default'=>0],
                    ['text'=>'上傳完成', 'value'=>4, 'default'=>0]
                ])
            ],
        ];
    }

    public function getListByFilters($menuDetails, $filters=[], $orderBy='created_at', $sort='DESC') {
        $query = $this->newModelQuery();

        if(Schema::hasColumn($this->table, 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        if ( count($filters) > 0) {
            if(!empty($filters['store_id'])) {
                $query->where('store_id', $filters['store_id']);
            }
            if(!empty($filters['audit_status'])) {
                $query->where('audit_status', $filters['audit_status']);
            }
            if(!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if(!empty($filters['main_user_id']) && !empty($filters['sub_user_id'])) {
                $query->where(function($q)use($filters){
                    $q->where('main_user_id', $filters['main_user_id'])
                      ->orWhere('sub_user_id', $filters['sub_user_id']);
                });
            }
            if(!empty($filters['audit_status'])) {
                $query->where(function($q)use($filters){
                    $q->where('audit_status', 1)
                      ->orWhere('audit_status', 2);
                });
            }
            if(!empty($filters['start_day']) && !empty($filters['end_day'])) {
                $query->whereBetween('audit_day', [$filters['start_day'], $filters['end_day']]);
            }
            if(!empty($filters['store'])) {
                $arr = [];
                $stores = app(Store::class)->where('name', 'like', '%'.$filters['store'].'%')->get();

                if ($stores->count() > 0) {
                    foreach ($stores as $store) {
                        if (!in_array($store->id, $arr)) {
                            array_push($arr, $store->id);
                        }
                    }
                    if (count($arr) > 0) {
                        $query->whereIn('store_id', $arr);
                    } else {
                        $query->where('id', false);
                    }
                } else {
                    $query->where('id', false);
                }
            }
            if(!empty($filters['name'])) {
                $arr = [];
                $users = app(User::class)->where('name', 'like', '%'.$filters['name'].'%')->get();

                if ($users->count() > 0) {
                    foreach ($users as $user) {
                        if (!in_array($user->id, $arr)) {
                            array_push($arr, $user->id);
                        }
                    }
                    if (count($arr) > 0) {
                        $query->where(function($q)use($arr){
                            $q->whereIn('main_user_id', $arr)
                              ->orWhereIn('sub_user_id', $arr);
                        });
                    } else {
                        $query->where('id', false);
                    }
                } else {
                    $query->where('id', false);
                }
            }
        }

        $query->orderBy($orderBy, $sort);
        $results = $query->paginate($filters['rows']??10);
        $results->appends($filters);

        return $results;
    }

    public function getAllDataByFilters($menuDetails, $filters=[], $orderBy='created_at', $sort='DESC') {
        $query = $this->newModelQuery();

        if(Schema::hasColumn($this->table, 'deleted_at')) {
            $query->whereNull('deleted_at');
        }
        if (!empty($filters) && count($filters) > 0) {
            if (!empty($filters['user_id'])) {
                $query->where(function($q)use($filters){
                    $q->where('main_user_id', $filters['user_id'])
                      ->orWhere('sub_user_id', $filters['user_id']);
                });
            }
            if (!empty($filters['now'])) {
                $query->where('audit_day', $filters['now']);
            }

            if(!empty($filters['start_day']) && !empty($filters['end_day'])) {
                $query->whereBetween('audit_day', [$filters['start_day'], $filters['end_day']]);
            }

            if(!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if(!empty($filters['audit_status'])) {
                $query->where(function($q)use($filters){
                    $q->where('audit_status', 1)
                      ->orWhere('audit_status', 2);
                });
            }

            if(!empty($filters['store'])) {
                $arr = [];
                $stores = app(Store::class)->where('name', 'like', '%'.$filters['store'].'%')->get();

                if ($stores->count() > 0) {
                    foreach ($stores as $store) {
                        if (!in_array($store->id, $arr)) {
                            array_push($arr, $store->id);
                        }
                    }
                    if (count($arr) > 0) {
                        $query->whereIn('store_id', $arr);
                    } else {
                        $query->where('id', false);
                    }
                } else {
                    $query->where('id', false);
                }
            }
            if(!empty($filters['name'])) {
                $arr = [];
                $users = app(User::class)->where('name', 'like', '%'.$filters['name'].'%')->get();

                if ($users->count() > 0) {
                    foreach ($users as $user) {
                        if (!in_array($user->id, $arr)) {
                            array_push($arr, $user->id);
                        }
                    }
                    if (count($arr) > 0) {
                        $query->where(function($q)use($arr){
                            $q->whereIn('main_user_id', $arr)
                              ->orWhereIn('sub_user_id', $arr);
                        });
                    } else {
                        $query->where('id', false);
                    }
                } else {
                    $query->where('id', false);
                }
            }
        }

        $query->orderBy($orderBy, $sort);
        $results = $query->get();

        return $results;
    }

    public function getDataByFilters($filters=[], $orderBy='created_at', $sort='DESC') {
        $query = $this->newModelQuery();

        if(Schema::hasColumn($this->table, 'deleted_at')) {
            $query->whereNull('deleted_at');
        }
        if (!empty($filters) && count($filters) > 0) {
            
            if (!empty($filters['store_id'])) {
                $query->where('store_id', $filters['store_id']);
            }
            if (!empty($filters['audit_route_id'])) {
                $query->where('id', $filters['audit_route_id']);
            }
        }

        $query->orderBy($orderBy, $sort);
        $results = $query->first();

        return $results;
    }

    public function getMainUserSigneAttribute($img) {
        if ($img != '' && !is_null($img)) {
            return config('app.url').'/storage/'.$img;
        }
    }

    public function getSubUserSigneAttribute($img) {
        if ($img != '' && !is_null($img)) {
            return config('app.url').'/storage/'.$img;
        }
    }

    public function getStoreSigneAttribute($img) {
        if ($img != '' && !is_null($img)) {
            return config('app.url').'/storage/'.$img;
        }
    }

    public function getGovSigneAttribute($img) {
        if ($img != '' && !is_null($img)) {
            return config('app.url').'/storage/'.$img;
        }
    }

    public function getReportAttribute($img) {
        if ($img != '' && !is_null($img)) {
            return config('app.url').'/storage/'.$img;
        }
    }

    public function systemType()
    {
        return $this->belongsTo(SystemType::class, 'system_type_id');
    }

    public function store() {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function storeImgs() {
        return $this->hasMany(AuditRouteStoreImg::class, 'audit_route_id');
    }

    public function records() {
        return $this->hasMany(AuditRecord::class, 'audit_route_id');
    }

    public function pendingRecords() {
        return $this->hasMany(AuditRecord::class, 'audit_route_id')
            ->where('status', 2);
    }

    public function counseling() {
        return $this->hasOne(Counseling::class, 'audit_route_id');
    }

    public function auditRecordMainAttributes() {
        return $this->hasMany(AuditRecord::class, 'audit_route_id')
            ->select('main_attribute_id')
            ->groupBy('main_attribute_id');
    }

    public function failRecords() {
        return $this->hasMany(AuditRecord::class, 'audit_route_id')
            ->where('status', 2);
    }

    public function successRecords() {
        return $this->hasMany(AuditRecordSuccess::class, 'audit_route_id');
    }

    public function getRandNotChecks() {
        return $this->hasMany(AuditRecord::class, 'audit_route_id')
            ->inRandomOrder()
            ->where('status', 0)
            ->limit(8);
    }

    public function getNotApplicables() {
        return $this->hasMany(AuditRecord::class, 'audit_route_id')
            ->where('status', 3);
    }

    public function getMainFails() {
        $type = app(AuditFailType::class)->where('name', '主要缺失')->first();

        return $this->hasMany(AuditRecord::class, 'audit_route_id')->where('audit_fail_type_id', $type->id);
    }

    public function getSubFails() {
        $type = app(AuditFailType::class)->where('name', '次要缺失')->first();

        return $this->hasMany(AuditRecord::class, 'audit_route_id')->where('audit_fail_type_id', $type->id);
    }

    public function getImportFails() {
        $type = app(AuditFailType::class)->where('name', '主要缺失(重大)')->first();

        return $this->hasMany(AuditRecord::class, 'audit_route_id')->where('audit_fail_type_id', $type->id);
    }

    public function getValidatingRegulations($routeId, $storeId, $mainId) {
        return app(AuditRecord::class)->where('audit_route_id', $routeId)
                    ->where('store_id', $storeId)
                    ->where('main_attribute_id', $mainId)
                    ->where(function($q){
                        $q->where('status', 1)
                          ->orWhere('status', 2)
                          ->orWhere('status', 3);
                    })
                    ->get();
    }

    public function getRegulations($routeId, $storeId, $mainId) {
        return app(AuditRecord::class)->where('audit_route_id', $routeId)
                    ->where('store_id', $storeId)
                    ->where('main_attribute_id', $mainId)
                    ->get();
    }

    public function getLastStatusByStoreId($storeId) {
        return $this->where('store_id', $storeId)->orderBy('created_at', 'DESC')->first();
    }

    public function mainUser() {
        return $this->belongsTo(User::class, 'main_user_id');
    }

    public function subUser() {
        return $this->belongsTo(User::class, 'sub_user_id');
    }

    public function auditRecordFails() {
        return $this->hasMany(AuditRecordFail::class, 'audit_route_id');
    }

    public function pendingChecks() {
        return $this->hasMany(PendingCheck::class, 'audit_route_id');
    }
}
