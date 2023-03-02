<?php

namespace App\Http\Controllers\Apis;

use App\Models\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SelectApi extends Controller
{
    public function getGroupRoles(Request $request)
    {
        $roles = app(Role::class)->where('group_id', $request->group_id)->get();

        if ($roles->count() > 0)
        {
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
}
