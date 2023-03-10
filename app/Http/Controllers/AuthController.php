<?php

namespace App\Http\Controllers;

use App\Models\SystemType;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends BasicController
{
    public function loginForm()
    {
        $types = app(SystemType::class)->get();

        return view('auth.login', compact(
            'types'
        ));
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=>'required',
            'password'=>'required',
        ], [
            'email.required'=>'缺少帳號',
            'password.required'=>'缺少密碼',
        ]);

        if ($validator->fails())
        {
            return view('alerts.error',[
                'msg'=>$validator->errors()->all()[0],
                'redirectURL'=>route('loginForm')
            ]);
        }

        $credentials = $request->only('email', 'password');
        
        if (Auth::guard('web')->attempt($credentials, $request->remember))
        {
            $user = Auth::guard('web')->user();

            if ($user->status == 2) 
            {
                return view('alerts.error',[
                    'msg'=>'您的帳戶已被停權, 請洽管理人員',
                    'redirectURL'=>route('loginForm')
                ]);
            }

            if ($user->role) 
            {
                if ($user->role->systemType) 
                {
                    if ($request->system_type_id != $user->role->systemType->id) 
                    {
                        Auth::logout();
                        
                        return view('alerts.error',[
                            'msg'=>'登錄失敗，所屬系統有誤',
                            'redirectURL'=>route('loginForm')
                        ]);
                    }
                }
            }

            return view('alerts.success',[
                'msg'=>'登錄成功',
                'redirectURL'=> route('dashboard')
            ]);
        }

        return view('alerts.error',[
            'msg'=>'登錄失敗，帳/密是否有誤？',
            'redirectURL'=>route('loginForm')
        ]);
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('loginForm');
    }
}
