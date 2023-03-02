<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditRecord extends AbstractModel {
    use HasFactory;

    protected $table = 'audit_records';
    protected $guarded = [];

    public function getFieldProperties() {}

    public function mainAttribute() {
        return $this->belongsTo(MainAttribute::class, 'main_attribute_id');
    }

    public function subAttribute() {
        return $this->belongsTo(SubAttribute::class, 'sub_attribute_id');
    }

    public function regulation() {
        return $this->belongsTo(Regulation::class, 'regulation_id');
    }

    public function items() {
        return $this->hasMany(AuditRecordItem::class, 'audit_record_id');
    }

    public function fails() {
        return $this->hasMany(AuditRecordFail::class, 'audit_record_id');
    }

    public function success() {
        return $this->hasMany(AuditRecordSuccess::class, 'audit_record_id');
    }

    public function failType() {
        return $this->belongsTo(AuditFailType::class, 'audit_fail_type_id');
    }

    public function auditRoute() {
        return $this->belongsTo(AuditRoute::class, 'audit_route_id');
    }

    public function getAuditRecordsByRouteId($id) {
        return $this->where('audit_route_id', $id)->get();
    }

    public function getGroupData($routeId, $storeId) {
        return $this->select('main_attribute_id')
                    ->where('audit_route_id', $routeId)
                    ->where('store_id', $storeId)
                    ->groupBy('main_attribute_id')
                    ->get();
    }

    public function getFactByRegulationId($id) {
        return app(RegulationFact::class)->where('regulation_id', $id)
            ->inRandomOrder()
            ->first();
    }

    public function getListDataByFilters($filters=[], $orderBy='created_at', $sort='DESC') {
        $query = $this->newModelQuery();

        if (!empty($filters)) {
            if (!empty($filters['store_id'])) {
                $query->where('store_id', $filters['store_id']);
            }
            if (!empty($filters['audit_route_id'])) {
                $query->where('audit_route_id', $filters['audit_route_id']);
            }
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (!empty($filters['main_attribute_id'])) {
                $query->where('main_attribute_id', $filters['main_attribute_id']);
            }
            if (!empty($filters['sub_attribute_id'])) {
                $query->where('sub_attribute_id', $filters['sub_attribute_id']);
            }
            if (!empty($filters['name'])) {
                $userArr = [];
                $routeArr = [];
                $users = app(User::class)->where('name', 'like', '%'.$filters['name'].'%')->get();

                if ($users->count() > 0) {
                    foreach ($users as $user) {
                        if (!in_array($user->id, $userArr)) {
                            array_push($userArr, $user->id);
                        }
                    }
                    $routes = app(AuditRoute::class)->where(function($q)use($userArr){
                        $q->whereIn('main_user_id', $userArr)
                          ->orWhereIn('sub_user_id', $userArr);
                    })->get();

                    if ($routes->count() > 0) {
                        foreach($routes as $route) {
                            if(!in_array($route->id, $routeArr)) {
                                array_push($routeArr, $route->id);
                            }
                        }
                        if (count($routeArr) > 0) {
                            $query->whereIn('audit_route_id', $routeArr);
                        } else {
                            $query->where('id', false);
                        }
                    } else {
                        $query->where('id', false);
                    }
                } else {
                    $query->where('id', false);
                }
            }
            if (!empty($filters['store'])) {
                $storeArr = [];
                $routeArr = [];
                $stores = app(Store::class)->where('name', 'like', '%'.$filters['name'].'%')->get();

                if ($stores->count() > 0) {
                    foreach ($stores as $store) {
                        if (!in_array($store->id, $storeArr)) {
                            array_push($storeArr, $store->id);
                        }
                    }
                    $routes = app(AuditRoute::class)->whereIn('store_id', $storeArr)->get();

                    if ($routes->count() > 0) {
                        foreach($routes as $route) {
                            if(!in_array($route->id, $routeArr)) {
                                array_push($routeArr, $route->id);
                            }
                        }
                        if (count($routeArr) > 0) {
                            $query->whereIn('audit_route_id', $routeArr);
                        } else {
                            $query->where('id', false);
                        }
                    } else {
                        $query->where('id', false);
                    }
                } else {
                    $query->where('id', false);
                }
            }
            if(!empty($filters['start_day']) && !empty($filters['end_day'])) {
                $query->whereBetween('audit_day', [$filters['start_day'], $filters['end_day']]);
            }
        }

        $query->orderBy($orderBy, $sort);
        $results = $query->get();
        return $results;
    }

    public function getDataByFilters($filters=[], $orderBy='created_at', $sort='DESC') {
        $query = $this->newModelQuery();

        if (!empty($filters)) {
            if (!empty($filters['store_id'])) {
                $query->where('store_id', $filters['store_id']);
            }
            if (!empty($filters['audit_route_id'])) {
                $query->where('audit_route_id', $filters['audit_route_id']);
            }
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (!empty($filters['main_attribute_id'])) {
                $query->where('main_attribute_id', $filters['main_attribute_id']);
            }
            if (!empty($filters['sub_attribute_id'])) {
                $query->where('sub_attribute_id', $filters['sub_attribute_id']);
            }
            if (!empty($filters['name'])) {
                $userArr = [];
                $routeArr = [];
                $users = app(User::class)->where('name', 'like', '%'.$filters['name'].'%')->get();

                if ($users->count() > 0) {
                    foreach ($users as $user) {
                        if (!in_array($user->id, $userArr)) {
                            array_push($userArr, $user->id);
                        }
                    }
                    $routes = app(AuditRoute::class)->where(function($q)use($userArr){
                        $q->whereIn('main_user_id', $userArr)
                          ->orWhereIn('sub_user_id', $userArr);
                    })->get();

                    if ($routes->count() > 0) {
                        foreach($routes as $route) {
                            if(!in_array($route->id, $routeArr)) {
                                array_push($routeArr, $route->id);
                            }
                        }
                        if (count($routeArr) > 0) {
                            $query->whereIn('audit_route_id', $routeArr);
                        } else {
                            $query->where('id', false);
                        }
                    } else {
                        $query->where('id', false);
                    }
                } else {
                    $query->where('id', false);
                }
            }
            if (!empty($filters['store'])) {
                $storeArr = [];
                $routeArr = [];
                $stores = app(Store::class)->where('name', 'like', '%'.$filters['name'].'%')->get();

                if ($stores->count() > 0) {
                    foreach ($stores as $store) {
                        if (!in_array($store->id, $storeArr)) {
                            array_push($storeArr, $store->id);
                        }
                    }
                    $routes = app(AuditRoute::class)->whereIn('store_id', $storeArr)->get();

                    if ($routes->count() > 0) {
                        foreach($routes as $route) {
                            if(!in_array($route->id, $routeArr)) {
                                array_push($routeArr, $route->id);
                            }
                        }
                        if (count($routeArr) > 0) {
                            $query->whereIn('audit_route_id', $routeArr);
                        } else {
                            $query->where('id', false);
                        }
                    } else {
                        $query->where('id', false);
                    }
                } else {
                    $query->where('id', false);
                }
            }
            if(!empty($filters['start_day']) && !empty($filters['end_day'])) {
                $query->whereBetween('audit_day', [$filters['start_day'], $filters['end_day']]);
            }
        }

        $query->orderBy($orderBy, $sort);
        $results = $query->paginate($filters['rows']??10);
        $results->appends($filters);

        return $results;
    }

    public function getAllDataByFilters($menuDetails, $filters=[], $orderBy='created_at', $sort='DESC') {
        $query = $this->newModelQuery();

        if (!empty($filters)) {
            if (!empty($filters['store_id'])) {
                $query->where('store_id', $filters['store_id']);
            }
            if (!empty($filters['audit_route_id'])) {
                $query->where('audit_route_id', $filters['audit_route_id']);
            }
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (!empty($filters['main_attribute_id'])) {
                $query->where('main_attribute_id', $filters['main_attribute_id']);
            }
            if (!empty($filters['sub_attribute_id'])) {
                $query->where('sub_attribute_id', $filters['sub_attribute_id']);
            }
            if (!empty($filters['name'])) {
                $userArr = [];
                $routeArr = [];
                $users = app(User::class)->where('name', 'like', '%'.$filters['name'].'%')->get();

                if ($users->count() > 0) {
                    foreach ($users as $user) {
                        if (!in_array($user->id, $userArr)) {
                            array_push($userArr, $user->id);
                        }
                    }
                    $routes = app(AuditRoute::class)->where(function($q)use($userArr){
                        $q->whereIn('main_user_id', $userArr)
                          ->orWhereIn('sub_user_id', $userArr);
                    })->get();

                    if ($routes->count() > 0) {
                        foreach($routes as $route) {
                            if(!in_array($route->id, $routeArr)) {
                                array_push($routeArr, $route->id);
                            }
                        }
                        if (count($routeArr) > 0) {
                            $query->whereIn('audit_route_id', $routeArr);
                        } else {
                            $query->where('id', false);
                        }
                    } else {
                        $query->where('id', false);
                    }
                } else {
                    $query->where('id', false);
                }
            }
            if (!empty($filters['store'])) {
                $storeArr = [];
                $routeArr = [];
                $stores = app(Store::class)->where('name', 'like', '%'.$filters['name'].'%')->get();

                if ($stores->count() > 0) {
                    foreach ($stores as $store) {
                        if (!in_array($store->id, $storeArr)) {
                            array_push($storeArr, $store->id);
                        }
                    }
                    $routes = app(AuditRoute::class)->whereIn('store_id', $storeArr)->get();

                    if ($routes->count() > 0) {
                        foreach($routes as $route) {
                            if(!in_array($route->id, $routeArr)) {
                                array_push($routeArr, $route->id);
                            }
                        }
                        if (count($routeArr) > 0) {
                            $query->whereIn('audit_route_id', $routeArr);
                        } else {
                            $query->where('id', false);
                        }
                    } else {
                        $query->where('id', false);
                    }
                } else {
                    $query->where('id', false);
                }
            }
            if(!empty($filters['start_day']) && !empty($filters['end_day'])) {
                $query->whereBetween('audit_day', [$filters['start_day'], $filters['end_day']]);
            }
        }

        $query->orderBy($orderBy, $sort);
        $results = $query->get();
        return $results;
    }
}
