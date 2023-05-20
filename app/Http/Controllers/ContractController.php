<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\ContractProductLog;

class ContractController extends BasicController
{
    public function store(Request $request)
    {
        if ($request->user()->cannot('create_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
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
            $formData = $request->except('_token', 'products');
            $formData['id'] = uniqid();
            $products = $request->products;

            if ($this->model->checkColumnExist('create_user_id'))
            {
                $formData['create_user_id'] = Auth::user()->id;
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

                $id = $this->model->createData($formData);
                DB::commit();

                $this->proccessProducts($id, $products);
            
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

    public function edit(Request $request, $id)
    {
        if ($request->user()->cannot('edit_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $data = $this->model->find($id);
        $products = $this->model->getProductsByType($data->product_type_id);

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

        return view($this->editView, [
            'data'=>$data,
            'id'=>$id,
            'products'=>$products,
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->user()->cannot('update_'.$this->slug,  $this->model))
        {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
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
            $formData = $request->except('_token', '_method', 'products');
            $products = $request->products;

            if ($this->model->checkColumnExist('update_user_id'))
            {
                $formData['update_user_id'] = Auth::user()->id;
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

                $this->proccessProducts($id, $products);
    
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

    public function getProducts(Request $request)
    {
        $logs = [];
        $filters = [
            'product_type_id' => $request->product_type_id
        ];

        if (isset($request->contract_id))
        {
            $data = $this->model->find($request->contract_id);
            $logs = $this->getLogs($data);
        }

        $products = app(Product::class)->getDataByFilters($filters);
        $content = view('contracts.products_table', compact('products', 'logs'))->render();

        return response()->json([
            'status'=>true,
            'message'=>'取得資料成功',
            'data'=>$content
        ], 200);
    }

    private function proccessProducts($id, $products=[])
    {
        if (!empty($products))
        {
            app(ContractProductLog::class)->where('contract_id', $id)->delete();

            foreach ($products??[] as $productId)
            {
                $product['id'] = uniqid();
                $product['contract_id'] = $id;
                $product['product_id'] = $productId;
                app(ContractProductLog::class)->create($product);
            }
        }
    }

    private function getLogs($data)
    {
        $arr = [];

        foreach ($data->logs??[] as $log)
        {
            $arr[$log->product_id] = 1;
        }

        return $arr;
    }
}
