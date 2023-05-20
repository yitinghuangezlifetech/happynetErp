<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\FuncType;

class ApplyController extends BasicController
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

        $types = app(FuncType::class)->getChildsByTypeCode('product_types');

        if(view()->exists($this->slug.'.create'))
        {
            $this->createView = $this->slug.'.create';
        }

        return view($this->createView, compact('types'));
    }
}
