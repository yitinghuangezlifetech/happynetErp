<?php

namespace App\Http\Controllers;

use Excel;
use Illuminate\Http\Request;

use App\Models\FuncType;
use App\Imports\DialRecordsImport;

class DialRecordController extends BasicController
{
    public function index(Request $request)
    {
        if ($request->user()->cannot('browse_' . $this->slug,  $this->model)) {
            return view('alerts.error', [
                'msg' => '您的權限不足, 請洽管理人員開通權限',
                'redirectURL' => route('dashboard')
            ]);
        }

        $filters = $this->getFilters($request);

        try {
            $list = $this->model->getListByFilters($this->menu->menuDetails, $filters);
        } catch (\Exception $e) {
            $list = (new Collection([]))->paginate(20);
        }

        $rateTypes = app(FuncType::class)->where('type_code', 'rate_types')->first();
        $dailRecordType = app(FuncType::class)->where('type_code', 'dial_record_types')->first();


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
            'list' => $list,
            'dailRecordType' => $dailRecordType,
            'rateTypes' => $rateTypes,
        ]);
    }

    public function importData(Request $request)
    {
        ini_set('max_execution_time', 0);

        $rateType = $request->reate_type;
        $type = app(FuncType::class)->find($request->dail_record_type_id);

        Excel::import(new DialRecordsImport($type, $rateType, $this), $request->file->store($this->slug));

        return view('alerts.success', [
            'msg' => '資料匯入成功',
            'redirectURL' => route($this->slug . '.index')
        ]);
    }

    private function showErrowMsg($msg)
    {
        return view('alerts.error', [
            'msg' => $msg,
            'redirectURL' => route($this->slug . '.index')
        ]);
    }
}
