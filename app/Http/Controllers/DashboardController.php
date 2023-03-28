<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends BasicController
{
    public function index(Request $request)
    {
        view()->share([
            'mainMenu'=>'',
            'title'=>'Dashboard',
            'routeName'=>'dashboard',
            'breadcrumb'=>[
                ['name'=>'Dashboard', 'active'=>false, 'breadUrl'=>false]
            ]
        ]);

        return view('dashboard');
    }
}
