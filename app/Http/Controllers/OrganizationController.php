<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Group;
use App\Models\FeeRate;
use App\Models\Identity;
use App\Models\Organization;
use App\Models\OrganizationType;

class OrganizationController extends BasicController
{
    public function create(Request $request)
    {
        if ($request->user()->cannot('create_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        if(view()->exists($this->slug.'.create'))
        {
            $this->createView = $this->slug.'.create';
        }

        $identities = app(Identity::class)->get();
        $groups = app(Group::class)->orderBy('sort', 'ASC')->get();
        $feeRates = app(FeeRate::class)->where('status', 1)->get();
        $organizationTypes = app(OrganizationType::class)->get();
        $organizations = app(Organization::class)
            ->where('status', 1)
            ->orderBy('created_at', 'DESC')
            ->get();

        return view($this->createView, compact(
            'identities' , 'groups', 'feeRates',
            'organizations', 'organizationTypes'
        ));
    }

    public function edit(Request $request, $id)
    {
        if ($request->user()->cannot('edit_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $identities = app(Identity::class)->get();
        $groups = app(Group::class)->orderBy('sort', 'ASC')->get();
        $feeRates = app(FeeRate::class)->where('status', 1)->get();
        $organizationTypes = app(OrganizationType::class)->get();
        $organizations = app(Organization::class)
            ->where('status', 1)
            ->orderBy('created_at', 'DESC')
            ->get();

        $data = $this->model->find($id);

        if (!$data)
        {
            return view('alerts.error',[
                'msg'=>'資料不存在',
                'redirectURL'=>route($this->slug.'.index')
            ]); 
        }

        if(view()->exists($this->slug.'.edit'))
        {
            $this->editView = $this->slug.'.edit';
        }

        return view($this->editView, compact(
            'identities' , 'groups', 'feeRates',
            'organizations', 'data', 'organizationTypes'
        ));
    }
}
