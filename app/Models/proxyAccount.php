<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class proxyAccount extends AbstractModel
{
    protected $table = 'proxy_accounts';
    protected $guarded = [];

    public function getFieldProperties() {
        return [
            [
                'field' => 'user_id',
                'type' => 'select',
                'show_name' => '使用者',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'user_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'user_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['user_id.required'=>'請選擇使用者']
                ]),
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 2
            ],
        ];
    }

    public function getListByFilters($menuDetails, $filters=[], $orderBy='created_at', $sort='DESC')
    {
        $query = $this->newModelQuery();

        if(Schema::hasColumn($this->table, 'deleted_at'))
        {
            $query->whereNull('deleted_at');
        }

        if ( count($filters) > 0)
        {
            if (!empty($filters['organization_id']))
            {
                $arr = [];
                $organization = app(Organization::class)->find($filters['organization_id']);

                foreach ($organization->childs??[] as $child)
                {
                    if (!in_array($child->id, $arr))
                    {
                        array_push($arr, $child->id);
                    }
                }

                if (count($arr) > 0)
                {
                    $query->whereIn('organization_id', $arr);
                }
                else
                {
                    $query->where('id', false);
                }
            }

            if (!empty($filters['keyword']))
            {
                $arr = [];

                $users = app(User::class)->where(function($q)use($filters){
                    $q->where('account', 'like', '%'.$filters['keyword'].'%')
                      ->orWhere('name', 'like', '%'.$filters['keyword'].'%')
                      ->orWhere('telecom_number', 'like', '%'.$filters['keyword'].'%');
                })
                ->get();

                foreach ($users??[] as $user)
                {
                    if (!in_array($user->id, $arr))
                    {
                        array_push($arr, $user->id);
                    }
                }

                if (count($arr) > 0)
                {
                    $query->whereIn('user_id', $arr);
                }
                else
                {
                    $query->where('id', false);
                }
            }
        }

        $query->orderBy($orderBy, $sort);
        $results = $query->paginate($filters['rows']??10);
        $results->appends($filters);

        return $results;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function createUser()
    {
        return $this->belongsTo(User::class, 'create_user_id');
    }
}
