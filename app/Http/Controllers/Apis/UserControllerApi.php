<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UserControllerApi extends Controller
{
    public function login(Request $request)
    {
        // 檢查HAPPYNET-APIKEY是否存在於header中
        if (!$request->header('apikey')) {
            return response()->json(['error' => 'Missing API Key'], 401);
        }

        // 從請求中獲取傳遞的參數
        $userId = $request->input('userId');
        // 檢查 signature 是否有效
        if (!$this->isValidUserId($userId)) {
            return response()->json([
                'error' => 'Invalid userId'
            ], 401);
        }

        // 從請求中獲取傳遞的參數
        $signature = $request->input('signature');

        $localServerKey = env('HAPPYNET_SERVERKEY');
        $hash = hash('sha256', $userId . $localServerKey);

        // 檢查簽名是否正確
        if ($hash !== $signature) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // 檢查 signature 是否有效
        if (!$this->isValidSignature($signature)) {
            return response()->json([
                'error' => 'Invalid signature' . $signature
            ], 401);
        }



        return response()->json(['msg' => 'success'], 200);
    }

    private function isValidUserId($userId)
    {
        // 實作檢查 signature 是否有效的邏輯
        // ...
        return true;
    }

    private function isValidSignature($signature)
    {
        // 實作檢查 signature 是否有效的邏輯
        // ...
        if (($signature == null) == 1) {
            return false;
        }
        return true;
    }
}
