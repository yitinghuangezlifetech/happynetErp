<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Staff;

class CheckApi extends Controller
{
    public function checkStaffCodeExist(Request $request)
    {
        $data = app(Staff::class)->where('staff_code', $request->code)->first();

        if ($data) {
            return response()->json([
                'status' => false,
                'message' => '該員工編號己存在',
                'data' => null
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => '該員工編號可以使用',
            'data' => null
        ], 200);
    }

    public function checkStaffEmailExist(Request $request)
    {
        $data = app(Staff::class)->where('email', $request->email)->first();

        if ($data) {
            return response()->json([
                'status' => false,
                'message' => '該email己存在',
                'data' => null
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => '該email可以使用',
            'data' => null
        ], 200);
    }
}
