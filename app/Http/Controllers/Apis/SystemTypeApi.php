<?php

namespace App\Http\Controllers\Apis;

use App\Models\Group;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemTypeApi extends Controller
{
    public function getGroupSystemTypes(Request $request)
    {
        $data = app(Group::class)->find($request->groupId);

        if ($data)
        {
            $types = $data->systemTypes;

            if ($types->count() > 0) {
                return response()->json([
                    'status' => true,
                    'message' => '取得資料成功',
                    'data' => $types
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => '群組尚未綁定系統類別',
                'data' => NULL
            ], 400);
        }

        return response()->json([
            'status' => false,
            'message' => '群組不存在',
            'data' => NULL
        ], 404);
    }
}
