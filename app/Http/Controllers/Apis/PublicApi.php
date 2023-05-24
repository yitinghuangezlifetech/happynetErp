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
            $logs = [];

            foreach ($data->products??[] as $info)
            {
                $logs[$info->product_type_id][$info->product_id] = 1;
            }

            $obj = app(Product::class);
            $termTypes = app(FuncType::class)->getChildsByTypeCode('term_types');
            $types = app(FuncType::class)->getChildsByTypeCode('product_types');

            $content = view('applies.product_item', compact(
                'logs', 'obj', 'termTypes', 'types'
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
