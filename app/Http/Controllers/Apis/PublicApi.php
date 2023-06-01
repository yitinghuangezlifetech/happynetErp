<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Product;
use App\Models\Project;
use App\Models\Contract;
use App\Models\FuncType;
use App\Models\Organization;

class PublicApi extends Controller
{
    public function getUserInfo(Request $request)
    {
        $user = app(User::class)->find($request->user_id);

        if ($user)
        {
            return response()->json([
                'status' => false,
                'message' => '取得資料成功',
                'data' => $user
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => '該使用者不存在, 請洽工程師',
            'data' => null
        ], 404);
    }

    public function getProjectProducts(Request $request)
    {
        $data = app(Project::class)->find($request->project_id);

        if ($data)
        {
            $logs = [];

            foreach ($data->logs??[] as $k=>$log)
            {
                $logs[$log->product_type_id][$log->product_id] = 1;
            }

            $obj = app(Product::class);

            $content = view('applies.product_item', compact(
                'logs', 'obj',
            ))->render();

            return response()->json([
                'status' => false,
                'message' => '取得資料成功',
                'data' => $content
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => '該專案不存在, 請洽工程師',
            'data' => null
        ], 404);
    }

    public function getContractProducts(Request $request)
    {
        $data = app(Contract::class)->find($request->contract_id);

        if ($data)
        {
            $arr = [];

            foreach ($apply->productLogs??[] as $log)
            {
                if ($log->qty > 0) {
                    $arr[$log->product_type_id][$log->product_id]['qty'] = $log->qty;
                    $arr[$log->product_type_id][$log->product_id]['rent_month'] = $log->rent_month;
                    $arr[$log->product_type_id][$log->product_id]['discount'] = $log->discount;
                    $arr[$log->product_type_id][$log->product_id]['amount'] = $log->amount;
                    $arr[$log->product_type_id][$log->product_id]['security_deposit'] = $log->security_deposit;
                    $arr[$log->product_type_id][$log->product_id]['note'] = $log->note;
                } else {
                    foreach($log->feeRateLogs??[] as $k=>$rate)
                    {
                        $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['call_target_id'] = $rate->call_target_id;
                        $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['call_rate'] = $rate->call_rate;
                        $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['discount'] = $rate->discount	;
                        $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['amount'] = $rate->amount;
                        $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['charge_unit'] = $rate->charge_unit;
                        $arr[$log->product_type_id][$log->product_id][$rate->call_target_id]['parameter'] = $rate->parameter;
                    }
                }
            }

            $applyLogs = $arr;

            $content = view('applies.product_item', compact(
                'applyLogs', 'data'
            ))->render();

            return response()->json([
                'status' => false,
                'message' => '取得資料成功',
                'data' => $content
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => '該合約不存在, 請洽工程師',
            'data' => null
        ], 404);
    }

    public function getContractRegulations(Request $request)
    {
        $data = app(Contract::class)->find($request->contract_id);

        if ($data)
        {
            $content = view('applies.regulation_item', compact(
                'data'
            ))->render();

            return response()->json([
                'status' => false,
                'message' => '取得資料成功',
                'data' => $content
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => '該合約不存在, 請洽工程師',
            'data' => null
        ], 404);
    }

    public function getOrganizationByIdentity(Request $request)
    {
        $organizations = app(Organization::class)->where('identity_id', $request->identity)->get();

        if ($organizations->count() > 0)
        {
            return response()->json([
                'status' => true,
                'message' => '取得資料成功',
                'data' => $organizations
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => '客戶身份無任何所屬組織存在',
            'data' => null
        ], 404);
    }

    public function getOrganization(Request $request)
    {
        $organization = app(Organization::class)->find($request->id);

        if ($organization)
        {
            return response()->json([
                'status' => true,
                'message' => '取得資料成功',
                'data' => $organization
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => '客戶身份無任何所屬組織存在',
            'data' => null
        ], 404);
    }

    public function getOrganizationUsers(Request $request)
    {
        $users = app(User::class)
            ->where('organization_id', $request->organization_id)
            ->where('status', 1)
            ->get();
        
        if ($users->count() > 0)
        {
            return response()->json([
                'status' => true,
                'message' => '取得資料成功',
                'data' => $users
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => '該組織無任何使用帳戶',
            'data' => null
        ], 404);
    }
    
}
