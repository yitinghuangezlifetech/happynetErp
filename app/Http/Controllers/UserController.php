<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends BasicController
{
    public function profile()
    {
        $data = Auth::guard('admin')->user();
        return view('users.profile', compact('data'));
    }

    public function store(Request $request)
    {
        if ($request->user()->role->super_admin != 1)
        {
            if ($request->user()->cannot('create_'.$this->slug,  $this->model))
            {
                return view('alerts.error', [
                    'msg' => '您的權限不足, 請洽管理人員開通權限',
                    'redirectURL' => '/'
                ]);
            }
        }

        $validator = $this->createRule($request->all());

        if (!is_array($validator) && $validator->fails())
        {
            return view('alerts.error',[
                'msg'=>$validator->errors()->all()[0],
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }

        DB::beginTransaction();

        try {
            $formData = $request->except('_token');
            $formData['id'] = uniqid();
            $formData['password'] = Hash::make($formData['password']);

            if ($this->menu->menuDetails->count() > 0)
            {
                foreach ($this->menu->menuDetails as $detail)
                {
                    if (isset($formData[$detail->field]))
                    {
                        if ($detail->type == 'image' || $detail->type == 'file')
                        {
                            if (is_object($formData[$detail->field]) && $formData[$detail->field]->getSize() > 0)
                            {
                                $formData[$detail->field] = $this->storeFile($formData[$detail->field], $this->slug);
                            }
                        }
                    }
                }

                $this->model->createData($formData);

                DB::commit();
            
                return view('alerts.success', [
                    'msg'=>'資料新增成功',
                    'redirectURL'=>route($this->slug.'.index')
                ]);
            }

            DB::rollBack();

            return view('alerts.error', [
                'msg'=>'資料新增失敗, 無該功能項之細項設定',
                'redirectURL'=>route($this->slug.'.index')
            ]);

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            return view('alerts.error',[
                'msg'=>$e->getMessage(),
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        if ($request->user()->role->super_admin != 1)
        {
            if ($request->user()->cannot('update_'.$this->slug,  $this->model))
            {
                return view('alerts.error', [
                    'msg' => '您的權限不足, 請洽管理人員開通權限',
                    'redirectURL' => '/'
                ]);
            }
        }

        $validator = $this->updateRule($request->all());

        if (!is_array($validator) && $validator->fails())
        {
            return view('alerts.error',[
                'msg'=>$validator->errors()->all()[0],
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }

        DB::beginTransaction();

        try {
            $formData = $request->except('_token', '_method');

            if (isset($formData['password']) && !empty($formData['password']))
            {
                $formData['password'] = Hash::make($formData['password']);
            } 
            else
            {
                unset($formData['password']);
            }

            if ($this->menu->menuDetails->count() > 0)
            {
                foreach ($this->menu->menuDetails as $detail)
                {
                    if (isset($formData[$detail->field]))
                    {
                        if ($detail->type == 'image' || $detail->type == 'file')
                        {
                            if (is_object($formData[$detail->field]) && $formData[$detail->field]->getSize() > 0)
                            {
                                $formData[$detail->field] = $this->storeFile($formData[$detail->field], $this->slug);
                            }
                        }
                    }
                }

                $this->model->updateData($id, $formData);

                DB::commit();
    
                return view('alerts.success',[
                    'msg'=>'資料更新成功',
                    'redirectURL'=>route($this->slug.'.index')
                ]);
            }

            DB::rollBack();

            return view('alerts.error', [
                'msg'=>'資料更新失敗, 無該功能項之細項設定',
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }
        catch (\Exception $e)
        {
            DB::rollBack();

            return view('alerts.error',[
                'msg'=>$e->getMessage(),
                'redirectURL'=>route($this->slug.'.index')
            ]);
        }
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $formData = $request->except('_token', '_method');

        if (!empty($request->password))
        {
            $formData['password'] = Hash::make($formData);
        } 
        else
        {
            unset($formData['password']);
        }

        if (isset($request->avatar) && $request->avatar->getSize() > 0)
        {
            $formData['avatar'] = $this->storeFile('avatar', $this->slug);
            $this->deleteFile( $data->avatar );
        }

        app(User::class)->updateData($user->id, $formData);

        return view('alerts.success',[
            'msg'=>'資料更新成功',
            'redirectURL'=>route('users.index')
        ]);
    }
}
