<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\UserAuth;
use App\Models\Organization;
use Illuminate\Http\Request;

class proxyAccountController extends BasicController
{
    public function index(Request $request) 
    {
        if ($request->user()->cannot('browse_'.$this->slug,  $this->model)) 
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }
        
        $user = Auth::user();

        $filters = [
            'organization_id' => $user->organization_id,
            'keyword' => $request->keyword
        ];

        try
        {
            $list = $this->model->getListByFilters($this->menu->menuDetails, $filters);
        }
        catch (\Exception $e)
        {
            $list = (new Collection([]))->paginate(20); 
        }

        if(view()->exists($this->slug.'.index')) 
        {
            $this->indexView = $this->slug.'.index';
        } 
        else 
        {
            if ($this->menu->sortable_enable == 1) 
            {
                $this->indexView = 'templates.sortable';
            }
            else
            {
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
        if ($request->user()->cannot('create_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $users = $this->getChildUsers();

        if(view()->exists($this->slug.'.create'))
        {
            $this->createView = $this->slug.'.create';
        }

        return view($this->createView, compact('users'));
    }

    public function proxyLogin($id)
    {
        $user = app(UserAuth::class)->find($id);

        Auth::guard('proxy')->login($user);

        return redirect()->route('proxy.dashboard');
    }

    private function getChildUsers()
    {
        $user = Auth::user();

        if (!empty($user->organization_id))
        {
            $organization = app(Organization::class)->find($user->organization_id);

            if ($organization)
            {
                $arr = [];

                foreach ($organization->childs??[] as $child)
                {
                    if (!in_array($child->id, $arr))
                    {
                        array_push($arr, $child->id);
                    }
                }

                $users = app(User::class)->whereIn('organization_id', $arr)->get();

                return $users;
            }
        }
        else
        {
            $users = app(User::class)->orderBy('organization_id', 'ASC')->get();

            return $users;
        }

        return collect([]);
    }
}
