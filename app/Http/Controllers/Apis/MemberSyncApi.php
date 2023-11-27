<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\FuncType;
use Illuminate\Http\Request;

class MemberSyncApi extends Controller
{
    public function syncMember(Request $request)
    {
        if (!$request->header('apikey')) {
            return response()->json([
                'status' => false,
                'message' => 'header缺少api key',
                'data' => null
            ], 401);
        }

        $apiKey = $request->header('apikey');

        if ($this->apiAuthValidation($apiKey)) {
            $groupType = app(FuncType::class)->where('type_code', 'member')->first();
            $group = app(Group::class)->where('group_type_id', $groupType->id)->first();
            $userType = app(FuncType::class)->where('type_code', 'main_accounts')->first();

            if ($group) {
                $role = app(Role::class)
                    ->where('group_id', $group->id)
                    ->first();

                if ($role) {
                    app(User::class)->create([
                        'id' => uniqid(),
                        'group_id' => $group->id,
                        'role_id' => $role->id,
                        'user_type_id' => $userType->id,
                        'account' => $request->account,
                        'password' => $request->password,
                        'name' => $request->account,
                        'status' => 1
                    ]);
                }
            }
        }
    }
}
