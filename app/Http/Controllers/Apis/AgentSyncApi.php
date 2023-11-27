<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Identity;
use App\Models\Organization;
use App\Models\OrganizationType;
use Illuminate\Http\Request;

class AgentSyncApi extends Controller
{
    public function syncAgent(Request $request)
    {
        if (!$request->header('apikey')) {
            return response()->json([
                'status' => false,
                'message' => 'header缺少api key',
                'data' => null
            ], 401);
        }

        $apiKey = $request->header('apikey');

        if ($this->apiAuthValidation($apiKey)) {
            $identity = app(Identity::class)->where('name', '經銷商')->first();
            $organizationType = app(OrganizationType::class)->where('name', '企業')->first();
            $groupType = app(FuncType::class)->where('type_code', 'agent')->first();
            if ($groupType) {
                $group = app(Group::class)->where('group_type_id', $groupType->id)->first();
                if ($group) {
                    $agent = $request->all();

                    $log = app(Organization::class)->where('id_no', $agent['company_no'])->first();

                    if (!$log) {
                        app(Organization::class)->create([
                            'id' => uniqid(),
                            'identity_id' => $identity->id,
                            'organization_type_id' => $organizationType->id,
                            'group_id' => $group->id,
                            'name' => $agent['name'],
                            'id_no' => $agent['company_no'],
                            'manager' => $agent['manager'],
                            'mobile' => $agent['mobile'],
                            'tel' => $agent['tel'],
                            'email' => $agent['email'],
                            'business_address' => $agent['company_address'],
                            'bill_address' => $agent['bill_address'],
                            'company_no_address' => $agent['company_address'],
                            'bill_day' => $agent['bill_day'],
                            'is_last_day' => $agent['is_last_day'],
                            'attach_number_limit' => 10000,
                            'phone_call_limit' => 10000,
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'apiKey驗證失敗',
            'data' => null
        ], 401);
    }
}
