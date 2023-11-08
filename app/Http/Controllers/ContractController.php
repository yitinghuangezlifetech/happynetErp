<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

use App\Models\FuncType;
use App\Models\Product;
use App\Models\ContractTermLog;
use App\Models\ContractProductLog;
use App\Models\ContractProductTypeLog;

class ContractController extends BasicController
{
    public function create(Request $request)
    {
        if ($request->user()->cannot('create_' . $this->slug,  $this->model)) {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $termTypes = app(FuncType::class)->getChildsByTypeCode('term_types');
        $types = app(FuncType::class)->getChildsByTypeCode('product_types');

        if (view()->exists($this->slug . '.create')) {
            $this->createView = $this->slug . '.create';
        }

        return view($this->createView, compact('termTypes', 'types'));
    }

    public function store(Request $request)
    {
        if ($request->user()->cannot('create_' . $this->slug,  $this->model)) {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $validator = $this->createRule($request->all());

        if (!is_array($validator) && $validator->fails()) {
            return view('alerts.error', [
                'msg' => $validator->errors()->all()[0],
                'redirectURL' => route($this->slug . '.index')
            ]);
        }

        DB::beginTransaction();

        try {
            $formData = $request->except('_token', 'products', 'regulations');
            $formData['id'] = uniqid();
            $products = $request->products;
            $regulations = $request->regulations;

            if ($this->model->checkColumnExist('create_user_id')) {
                $formData['create_user_id'] = Auth::user()->id;
            }

            if ($this->menu->menuDetails->count() > 0) {
                foreach ($this->menu->menuDetails as $detail) {
                    if (isset($formData[$detail->field])) {
                        if ($detail->type == 'image' || $detail->type == 'file') {
                            if (is_object($formData[$detail->field]) && $formData[$detail->field]->getSize() > 0) {
                                $formData[$detail->field] = $this->storeFile($formData[$detail->field], $this->slug);
                            }
                        }
                    }
                }

                $id = $this->model->createData($formData);
                DB::commit();

                $this->proccessProducts($id, $products);
                $this->proccessRegulations($id, $regulations);

                return view('alerts.success', [
                    'msg' => '資料新增成功',
                    'redirectURL' => route($this->slug . '.index')
                ]);
            }

            DB::rollBack();

            return view('alerts.error', [
                'msg' => '資料新增失敗, 無該功能項之細項設定',
                'redirectURL' => route($this->slug . '.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return view('alerts.error', [
                'msg' => $e->getMessage(),
                'redirectURL' => route($this->slug . '.index')
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        if ($request->user()->cannot('edit_' . $this->slug,  $this->model)) {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $data = $this->model->find($id);
        $logs = $this->getProductLog($data);
        $obj = app(Product::class);
        $termTypes = app(FuncType::class)->getChildsByTypeCode('term_types');
        $types = app(FuncType::class)->getChildsByTypeCode('product_types');


        if (!$data) {
            return view('alerts.error', [
                'msg' => '資料不存在',
                'redirectURL' => route($this->slug . '.index')
            ]);
        }

        if (view()->exists($this->slug . '.edit')) {
            $this->editView = $this->slug . '.edit';
        }

        return view($this->editView, [
            'data' => $data,
            'id' => $id,
            'logs' => $logs,
            'obj' => $obj,
            'types' => $types,
            'termTypes' => $termTypes,
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->user()->cannot('update_' . $this->slug,  $this->model)) {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }
        $validator = $this->updateRule($request->all());

        if (!is_array($validator) && $validator->fails()) {
            return view('alerts.error', [
                'msg' => $validator->errors()->all()[0],
                'redirectURL' => route($this->slug . '.index')
            ]);
        }

        DB::beginTransaction();

        try {
            $formData = $request->except('_token', '_method', 'products', 'regulations');
            $products = $request->products;
            $regulations = $request->regulations;

            if ($this->model->checkColumnExist('update_user_id')) {
                $formData['update_user_id'] = Auth::user()->id;
            }

            if ($this->menu->menuDetails->count() > 0) {
                foreach ($this->menu->menuDetails as $detail) {
                    if (isset($formData[$detail->field])) {
                        if ($detail->type == 'image' || $detail->type == 'file') {
                            if (is_object($formData[$detail->field]) && $formData[$detail->field]->getSize() > 0) {
                                $formData[$detail->field] = $this->storeFile($formData[$detail->field], $this->slug);
                            }
                        }
                    }
                }

                $this->model->updateData($id, $formData);

                DB::commit();

                $this->proccessProducts($id, $products);
                $this->proccessRegulations($id, $regulations);

                return view('alerts.success', [
                    'msg' => '資料更新成功',
                    'redirectURL' => route($this->slug . '.index')
                ]);
            }

            DB::rollBack();

            return view('alerts.error', [
                'msg' => '資料更新失敗, 無該功能項之細項設定',
                'redirectURL' => route($this->slug . '.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return view('alerts.error', [
                'msg' => $e->getMessage(),
                'redirectURL' => route($this->slug . '.index')
            ]);
        }
    }

    public function getProductLog($contract)
    {
        $arr = [];

        foreach ($contract->products ?? [] as $info) {
            $arr[$info->product_type_id][$info->product_id] = 1;
        }

        return $arr;
    }

    private function proccessProducts($id, $products = [])
    {
        if (!empty($products)) {
            app(ContractProductTypeLog::class)->where('contract_id', $id)->delete();
            app(ContractProductLog::class)->where('contract_id', $id)->delete();

            foreach ($products ?? [] as $info) {
                $log = app(ContractProductTypeLog::class)->create([
                    'id' => uniqid(),
                    'contract_id' => $id,
                    'product_type_id' => $info['product_type_id']
                ]);

                foreach ($info['items'] as $productId) {
                    $data['id'] = uniqid();
                    $data['contract_id'] = $id;
                    $data['log_id'] = $log->id;
                    $data['product_type_id'] = $info['product_type_id'];
                    $data['product_id'] = $productId;

                    app(ContractProductLog::class)->create($data);
                }
            }
        }
    }

    private function proccessRegulations($id, $regulations = [])
    {
        if (!empty($regulations)) {
            app(ContractTermLog::class)->where('contract_id', $id)->delete();

            foreach ($regulations ?? [] as $regulation) {
                $data['id'] = uniqid();
                $data['contract_id'] = $id;
                $data['term_id'] = $regulation['term_id'];
                $data['sort'] = $regulation['sort'];

                app(ContractTermLog::class)->create($data);
            }
        }
    }

    private function getLogs($data)
    {
        $arr = [];

        foreach ($data->logs ?? [] as $log) {
            $arr[$log->product_id] = 1;
        }

        return $arr;
    }
}
