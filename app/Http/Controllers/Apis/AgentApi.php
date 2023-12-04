<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Identity;
use App\Models\FuncType;
use App\Models\Organization;
use App\Models\OrganizationType;
use Illuminate\Http\Request;

use App\Http\Controllers\Traits\ApiAuthServiceTrait as apiAuth;

class AgentApi extends Controller
{
    use apiAuth;

    public function syncAgent(Request $request)
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

            $identity = app(Identity::class)->where('name', '經銷商')->first();
            $organizationType = app(OrganizationType::class)->where('name', '企業')->first();
            $dataSource = app(FuncType::class)->where('type_code', 'Web')->first();
            $agent = $request->agent;

            app(Organization::class)->create([
                'id' => uniqid(),
                'data_source_id' => $dataSource->id,
                'identity_id' => $identity->id,
                'organization_type_id' => $organizationType->id,
                'name' => $agent['account'],
                'bill_day' => $agent['bill_day'],
                'is_last_day' => $agent['is_last_day'],
                'attach_number_limit' => 10000,
                'phone_call_limit' => 10000,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'apiKey驗證失敗',
            'data' => null
        ], 401);
    }
}
