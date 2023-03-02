<?php

namespace App\Models;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends AbstractModel {
    use HasFactory, SoftDeletes;

    protected $table = 'users';
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getFieldProperties()
    {
        return [
            [
                'field' => 'group_id',
                'type' => 'select',
                'show_name' => '所屬群組',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 0,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Group',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'role_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'role_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['role_id.required'=>'請選擇所屬群組']
                ])
            ],
            [
                'field' => 'role_id',
                'type' => 'select',
                'show_name' => '所屬角色',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Role',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'role_id'=>'required'
                ]),
                'update_rule' => json_encode([
                    'role_id'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['role_id.required'=>'請選擇所屬角色']
                ])
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '姓名',
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
                    ['name.required'=>'姓名請勿空白']
                ])
            ],
            [
                'field' => 'email',
                'type' => 'email',
                'show_name' => '帳號',
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 3,
                'create_rule' => json_encode([
                    'email'=>'required'
                ]),
                'update_rule' => json_encode([
                    'email'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['email.required'=>'帳號請勿空白']
                ])
            ],
            [
                'field' => 'password',
                'type' => 'password',
                'show_name' => '密碼',
                'required' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 4,
                'create_rule' => json_encode([
                    'password'=>'required'
                ]),
                'error_msg' => json_encode([
                    ['password.required'=>'密碼請勿空白']
                ])
            ],
            [
                'field' => 'status',
                'type' => 'radio',
                'show_name' => '啟用狀態',
                'join_search' => 2,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 5,
                'options' => json_encode([
                    ['text'=>'啟用', 'value'=>'1', 'default'=>2],
                    ['text'=>'停用', 'value'=>'2', 'default'=>1],
                ])
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '資料建立日期',
                'browse' => 1,
                'sort' => 6
            ],
        ];
    }

    public function getList($filters = [])
    {
        $query = $this->newModelQuery();

        if (Schema::hasColumn($this->table, 'deleted_at'))
        {
            $query->whereNull('deleted_at');
        }

        if (!empty($filters) && count($filters) > 0)
        {
            
            if (!empty($filters['role_id']))
            {
                $query->where('role_id', $filters['role_id']);
            }
            if (!empty($filters['email']))
            {
                $query->where('email', 'like', '%'.$filters['email'].'%');
            }
            if (!empty($filters['name']))
            {
                $query->where('name', 'like', '%'.$filters['name'].'%');
            }
            if (!empty($filters['super_admin']) && !is_null($filters['super_admin']))
            {
                $query->where('super_admin', $filters['super_admin']);
            }
        }

        $query->orderBy('created_at', 'DESC');
        $results = $query->paginate($filters['rows']??10);
        $results->appends($filters);

        return $results;
    }

    public function getAvatarAttribute($img)
    {
        if ($img != '' && !is_null($img))
        {
            return config('app.url').'/storage/'.$img;
        }
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function hasPermission($action)
    {
        $permission = app(Permission::class)->where('code', $action)->first();

        if ($permission)
        {
            $hasPermission = app(RolePermission::class)
                ->where('role_id', $this->role_id)
                ->where('permission_id', $permission->id)
                ->first();

            if ($hasPermission)
            {
                return true;
            } 
        }
        return false;
    }
}
