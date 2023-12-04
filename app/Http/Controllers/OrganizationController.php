<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Models\Group;
use App\Models\FeeRate;
use App\Models\FuncType;
use App\Models\Organization;

class OrganizationController extends BasicController
{
    public function index(Request $request)
    {
        if ($request->user()->cannot('browse_' . $this->slug,  $this->model)) {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $user = Auth::user();

        $filters = $this->getFilters($request);

        if ($user->organization) {
            if ($user->role->super_admin != 1) {
                $filters['id'] = $user->organization->id;
                $filters['parent_id'] = $user->organization->id;
            }
        }

        $list = $this->model->getListByFilters($this->menu->menuDetails, $filters);

        if (view()->exists($this->slug . '.index')) {
            $this->indexView = $this->slug . '.index';
        } else {
            if ($this->menu->sortable_enable == 1) {
                $this->indexView = 'templates.sortable';
            } else {
                $this->indexView = 'templates.index';
            }
        }

        return view($this->indexView, [
            'filters' => $filters,
            'list' => $list
        ]);
    }

    public function create(Request $request)
    {
        if ($request->user()->cannot('create_' . $this->slug,  $this->model)) {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        if (view()->exists($this->slug . '.create')) {
            $this->createView = $this->slug . '.create';
        }

        $identities = app(FuncType::class)->where('type_code', 'identity_type')->first();
        $groups = app(Group::class)->orderBy('sort', 'ASC')->get();
        $feeRates = app(FeeRate::class)->where('status', 1)->get();
        $organizationTypes = app(FuncType::class)->where('type_code', 'org_type')->first();
        $organizations = $this->getChildOrganizations();
        $dataSource = app(FuncType::class)->where('type_code', 'data_source')->first();

        return view($this->createView, compact(
            'identities',
            'groups',
            'feeRates',
            'organizations',
            'organizationTypes',
            'dataSource',
        ));
    }

    public function edit(Request $request, $id)
    {
        if ($request->user()->cannot('edit_' . $this->slug,  $this->model)) {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $identities = app(FuncType::class)->where('type_code', 'identity_types')->first();
        $groups = app(Group::class)->orderBy('sort', 'ASC')->get();
        $feeRates = app(FeeRate::class)->where('status', 1)->get();
        $organizationTypes = app(FuncType::class)->where('type_code', 'org_types')->first();
        $organizations = $this->getChildOrganizations();

        $data = $this->model->find($id);

        if (!$data) {
            return view('alerts.error', [
                'msg' => '資料不存在',
                'redirectURL' => route($this->slug . '.index')
            ]);
        }

        if (view()->exists($this->slug . '.edit')) {
            $this->editView = $this->slug . '.edit';
        }

        return view($this->editView, compact(
            'identities',
            'groups',
            'feeRates',
            'organizations',
            'data',
            'organizationTypes'
        ));
    }

    private function getChildOrganizations()
    {
        $user = Auth::user();

        if (empty($user->organization)) {
            $organizations = app(Organization::class)
                ->where('status', 1)
                ->get();
        } else {
            $organizations = app(Organization::class)
                ->where(function ($q) use ($user) {
                    $q->where('id', $user->organization->id)
                        ->orWhere('parent_id', $user->organization->id);
                })
                ->where('status', 1)
                ->get();
        }


        return $organizations;
    }
}
