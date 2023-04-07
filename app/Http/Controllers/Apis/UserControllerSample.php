<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserControllerSample extends Controller
{
    public function login(Request $request)
    {
        // 檢查 header 中的 HAPPYNET-APIKEY 是否存在
        if (!$request->header('HAPPYNET-APIKEY') || $request->header('HAPPYNET-APIKEY') !== env('HAPPYNET_APIKEY')) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        // 檢查參數中的 userId 與 signature 是否存在
        if (!$request->has('userId') || !$request->has('signature')) {
            return response()->json(['error' => 'Missing userId or signature'], 400);
        }

        // 從參數中取得 userId 與 signature
        $userId = $request->input('userId');
        $signature = $request->input('signature');

        // 使用 server-key 與 userId 產生簽名
        $serverKey = env('SERVER_KEY');
        $hash = hash('sha256', $serverKey . $userId);

        // 檢查簽名是否正確
        if ($hash !== $signature) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // 使用 Laravel 內建的認證系統進行使用者驗證，驗證通過即登錄成功
        if (Auth::attempt(['id' => $userId])) {
            return response()->json(['message' => 'Login successful'], 200);
        }

        return response()->json(['error' => 'Invalid user ID'], 401);
    }
}
