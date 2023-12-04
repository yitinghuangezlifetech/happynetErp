<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Role;
use App\Models\Group;
use App\Models\FuncType;

use App\Http\Controllers\Traits\ApiAuthServiceTrait as apiAuth;

class MemberApi extends Controller
{
    use apiAuth;

    public function syncMember(Request $request)
    {
        if (!$request->header('ApiKey')) {
            return response()->json([
                'status' => false,
                'message' => 'header缺少api key',
                'data' => null
            ], 401);
        }

        $apiKey = $request->header('ApiKey');

        if ($this->apiAuthValidation($apiKey)) {

            $userType = app(FuncType::class)->where('type_code', 'main_accounts')->first();
            $dataSource = app(FuncType::class)->where('type_code', 'Web')->first();

            $member = $request->member;

            app(User::class)->create([
                'id' => uniqid(),
                'data_source_id' => $dataSource->id,
                'user_type_id' => $userType->id,
                'account' => $member['account'],
                'password' => $member['password'],
                'name' => $member['account'],
                'status' => 1
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'apiKey有誤或未生效',
            'data' => null
        ], 401);
    }

    public function checkAccountExist(Request $request)
    {
        if (!$request->header('ApiKey')) {
            return response()->json([
                'status' => false,
                'message' => 'header缺少api key',
                'data' => null
            ], 401);
        }

        $apiKey = $request->header('ApiKey');

        if ($this->apiAuthValidation($apiKey)) {

            $user = app(User::class)->where('account', $request->account)->first();

            if ($user) {
                return response()->json([
                    'status' => true,
                    'message' => '成功取得使用者資料',
                    'data' => $user
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => '該帳號不存在',
                    'data' => null
                ], 404);
            }
        }
    }
}
