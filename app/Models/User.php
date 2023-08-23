<?php

namespace App\Models;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends AbstractModel
{
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
                'field' => 'organization_id',
                'type' => 'select',
                'show_name' => '所屬組織',
                'join_search' => 2,
                'required' => 2,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'super_admin_use' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Organization',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'empty',
                'create' => 1,
                'edit' => 1,
            ],
            [
                'field' => 'group_id',
                'type' => 'select',
                'show_name' => '所屬群組',
                'join_search' => 2,
                'required' => 1,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'super_admin_use' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Group',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'role_id' => 'required'
                ]),
                'update_rule' => json_encode([
                    'role_id' => 'required'
                ]),
                'error_msg' => json_encode([
                    ['role_id.required' => '請選擇所屬群組']
                ])
            ],
            [
                'field' => 'role_id',
                'type' => 'select',
                'show_name' => '所屬角色',
                'join_search' => 2,
                'required' => 1,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'super_admin_use' => 1,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\Role',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ]),
                'create_rule' => json_encode([
                    'role_id' => 'required'
                ]),
                'update_rule' => json_encode([
                    'role_id' => 'required'
                ]),
                'error_msg' => json_encode([
                    ['role_id.required' => '請選擇所屬角色']
                ])
            ],

            [
                'field' => 'user_type_id',
                'type' => 'select',
                'show_name' => '所屬用戶類別',
                'join_search' => 2,
                'required' => 1,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 4,
                'has_relationship' => 1,
                'super_admin_use' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\UserType',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'account',
                'type' => 'text',
                'show_name' => '帳號',
                'use_edit_link' => 1,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 5,
                'create_rule' => json_encode([
                    'account' => 'required'
                ]),
                'update_rule' => json_encode([
                    'account' => 'required'
                ]),
                'error_msg' => json_encode([
                    ['account.required' => '帳號請勿空白']
                ])
            ],
            [
                'field' => 'password',
                'type' => 'password',
                'show_name' => '密碼',
                'use_edit_link' => 2,
                'join_search' => 2,
                'required' => 1,
                'browse' => 2,
                'create' => 1,
                'edit' => 1,
                'sort' => 6,
                'create_rule' => json_encode([
                    'password' => 'required'
                ]),
                'error_msg' => json_encode([
                    ['password.required' => '密碼請勿空白']
                ])
            ],
            [
                'field' => 'telecom_number',
                'type' => 'text',
                'show_name' => '電信號碼',
                'use_edit_link' => 2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 7,
                'create_rule' => json_encode([
                    'telecom_number' => 'required'
                ]),
                'update_rule' => json_encode([
                    'telecom_number' => 'required'
                ]),
                'error_msg' => json_encode([
                    ['telecom_number.required' => '電信號碼請勿空白']
                ])
            ],
            [
                'field' => 'name',
                'type' => 'text',
                'show_name' => '使用者名稱',
                'use_edit_link' => 2,
                'join_search' => 1,
                'required' => 1,
                'browse' => 1,
                'create' => 1,
                'edit' => 1,
                'sort' => 8,
                'create_rule' => json_encode([
                    'name' => 'required'
                ]),
                'update_rule' => json_encode([
                    'name' => 'required'
                ]),
                'error_msg' => json_encode([
                    ['name.required' => '姓名請勿空白']
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
                'super_admin_use' => 1,
                'options' => json_encode([
                    ['text' => '啟用', 'value' => '1', 'default' => 2],
                    ['text' => '停用', 'value' => '2', 'default' => 1],
                ])
            ],
            [
                'field' => 'create_user_id',
                'type' => 'select',
                'show_name' => '建立人員',
                'browse' => 1,
                'sort' => 10,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'created_at',
                'type' => 'date_time',
                'show_name' => '建立日期',
                'browse' => 1,
                'sort' => 11
            ],
            [
                'field' => 'update_user_id',
                'type' => 'select',
                'show_name' => '修改人員',
                'browse' => 1,
                'sort' => 12,
                'has_relationship' => 1,
                'relationship' => json_encode([
                    'model' => 'App\Models\User',
                    'references_field' => 'id',
                    'show_field' => 'name'
                ])
            ],
            [
                'field' => 'updated_at',
                'type' => 'date_time',
                'show_name' => '修改日期',
                'browse' => 1,
                'sort' => 13
            ],
        ];
    }

    public function getList($filters = [])
    {
        $query = $this->newModelQuery();

        if (Schema::hasColumn($this->table, 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        if (!empty($filters) && count($filters) > 0) {
            if (!empty($filters['id'])) {
                $query->where('id', $filters['id']);
            }
            if (!empty($filters['organization_id'])) {
                $arr = [$filters['organization_id']];

                $organization = app(Organization::class)->find($filters['organization_id']);
                if ($organization) {
                    foreach ($organization->childs ?? [] as $child) {
                        if (!in_array($child->id, $arr)) {
                            array_push($arr, $child->id);
                        }
                    }

                    if (count($arr) > 0) {
                        $query->whereIn('organization_id', $arr);
                    }
                }
            }
            if (!empty($filters['role_id'])) {
                $query->where('role_id', $filters['role_id']);
            }
            if (!empty($filters['email'])) {
                $query->where('email', 'like', '%' . $filters['email'] . '%');
            }
            if (!empty($filters['account'])) {
                $query->where('account', 'like', '%' . $filters['account'] . '%');
            }
            if (!empty($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }
        }

        $query->orderBy('created_at', 'DESC');
        $results = $query->paginate($filters['rows'] ?? 10);
        $results->appends($filters);

        return $results;
    }

    public function getAvatarAttribute($img)
    {
        if ($img != '' && !is_null($img)) {
            return config('app.url') . '/storage/' . $img;
        }
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function hasPermission($action)
    {
        $permission = app(Permission::class)->where('code', $action)->first();

        if ($permission) {
            $hasPermission = app(RolePermission::class)
                ->where('role_id', $this->role_id)
                ->where('permission_id', $permission->id)
                ->first();

            if ($hasPermission) {
                return true;
            }
        }
        return false;
    }
}
