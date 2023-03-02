<?php

namespace App\Http\Controllers\Reports;

use App\Models\AuditRecord;
use Illuminate\Http\Request;
use App\Exports\DataReportExport;
use Maatwebsite\Excel\Facades\Excel;

class AuditDataReport extends Report {
    public function index(Request $request) {

        $filters = [
            'name'=>$request->name,
            'store'=>$request->store,
            'start_day'=>$request->start_day,
            'end_day'=>$request->end_day,
            'status'=>2,
            'data'=>$request->data
        ];

        $data = $request->data;
        $list = app(AuditRecord::class)->getDataByFilters($filters);

        return view('reports.data_report', compact(
            'list', 'filters', 'data'
        ));
    }

    public function downloadExcel(Request $request) {
        $filters = [
            'name'=>$request->name,
            'store'=>$request->store,
            'start_day'=>$request->start_day,
            'end_day'=>$request->end_day,
            'status'=>2
        ];

        $list = app(AuditRecord::class)->getAllDataByFilters([], $filters);

        return Excel::download(new DataReportExport($list), date('Ymd').'_評核計劃系統數據表.xlsx');
    }
}
