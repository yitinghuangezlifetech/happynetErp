<?php

namespace App\Http\Controllers;


use DB;
use Auth;
use Excel;
use App\Imports\RateImport;
use App\Models\FeeRate;
use App\Models\FuncType;
use App\Models\FeeRateLog;
use App\Models\FeeRateTable;
use Illuminate\Http\Request;

class FeeRateController extends BasicController
{
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

        $rateTypes = app(FuncType::class)->getChildsByTypeCode('rate_types');
        $callTargets = app(FuncType::class)->getChildsByTypeCode('call_targets');

        return view($this->createView, compact(
            'rateTypes',
            'callTargets'
        ));
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
            $formData = $request->except('_token', 'fee_rate_file');
            $formData['id'] = uniqid();

            $inputFile = storage_path('app/public') . '/' . $request->fee_rate_file->store('csv');
            $outputFile = storage_path('app/public') . '/csv/' . $request->fee_rate_file->getClientOriginalName();

            $this->convertBig5ToUtf8($inputFile, $outputFile);

            if ($this->model->checkColumnExist('create_user_id')) {
                $formData['create_user_id'] = Auth::user()->id;
            }

            $data = $this->model->create($formData);
            DB::commit();

            Excel::import(new RateImport($data), $inputFile);

            return view('alerts.success', [
                'msg' => '費率表新增成功',
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
        $rateTypes = app(FuncType::class)->getChildsByTypeCode('rate_types');
        $callTargets = app(FuncType::class)->getChildsByTypeCode('call_targets');

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
            'rateTypes' => $rateTypes,
            'callTargets' => $callTargets,
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
            $formData = $request->except('_token', '_method');

            if ($this->model->checkColumnExist('update_user_id')) {
                $formData['update_user_id'] = Auth::user()->id;
            }

            $this->model->updateData($id, $formData);
            DB::commit();

            $data = app(FeeRate::class)->find($id);

            if ($request->fee_rate_file->getSize() > 0) {
                $inputFile = storage_path('app/public') . '/' . $request->fee_rate_file->store('csv');
                $outputFile = storage_path('app/public') . '/csv/' . $request->fee_rate_file->getClientOriginalName();
                $this->convertBig5ToUtf8($inputFile, $outputFile);

                Excel::import(new RateImport($data), $inputFile);
            }

            return view('alerts.success', [
                'msg' => '費率表編輯成功',
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

    public function destroy(Request $request, $id)
    {
        app(FeeRateTable::class)->where('fee_rate_id', $id)->delete();
        app(FeeRate::class)->where('id', $id)->delete();

        return view('alerts.success', [
            'msg' => '費率表刪除成功',
            'redirectURL' => route($this->slug . '.index')
        ]);
    }

    public function details(Request $request, $id)
    {
        $filters = [
            'fee_rate_id' => $id,
            'keyword' => $request->keyword
        ];

        $logs = app(FeeRateTable::class)->getSearchResult($filters);

        return view('fee_rates.details', compact(
            'id',
            'logs',
            'filters'
        ));
    }

    public function content($id, $detailId)
    {
        $data = app(FeeRateTable::class)->find($detailId);

        if ($data) {
            return view('fee_rates.detail_content', compact(
                'id',
                'data'
            ));
        }

        return view('alerts.error', [
            'msg' => '該資料不存在',
            'redirectURL' => route('fee_rates.details', $id)
        ]);
    }

    public function detailUpdate(Request $request, $id, $detailId)
    {
        $formData = $request->except('_token', '_method');

        app(FeeRateTable::class)->where('id', $detailId)->update($formData);

        return view('alerts.success', [
            'msg' => '資料更新成功',
            'redirectURL' => route('fee_rates.details', $id)
        ]);
    }

    private function convertBig5ToUtf8($inputFile, $outputFile)
    {
        $inputHandle = fopen($inputFile, 'r');
        $outputHandle = fopen($outputFile, 'w');

        while (($line = fgets($inputHandle)) !== false) {
            $utf8Line = mb_convert_encoding($line, 'UTF-8', 'Big5');
            fwrite($outputHandle, $utf8Line);
        }

        fclose($inputHandle);
        fclose($outputHandle);
    }
}
