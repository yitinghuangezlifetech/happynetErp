<?php

namespace App\Http\Controllers\Apis;

use App\Models\Role;
use App\Models\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SelectApi extends Controller
{
    public function getGroupRoles(Request $request)
    {
        $roles = app(Role::class)->where('group_id', $request->group_id)->get();

        if ($roles->count() > 0) {
            return response()->json([
                'status' => true,
                'message' => '取得資料成功',
                'data' => $roles
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => '取得資料成功',
            'data' => $roles
        ], 200);
    }

    public function getOrganizationRole(Request $request)
    {
        $data = app(Organization::class)->find($request->organization_id);

        if ($data) {
            $roles = app(Role::class)->where('group_id', $data->group_id)->get();

            if ($roles->count() > 0) {
                return response()->json([
                    'status' => true,
                    'message' => '取得資料成功',
                    'data' => $roles
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => '無任何角色資料，請洽系統管理員',
                'data' => null
            ], 404);
        } else {
            return response()->json([
                'status' => false,
                'message' => '無此組織資料',
                'data' => null
            ], 404);
        }
    }
}
