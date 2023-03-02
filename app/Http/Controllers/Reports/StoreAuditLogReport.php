<?php

namespace App\Http\Controllers\Reports;

use App\Models\AuditRoute;
use Illuminate\Http\Request;
use App\Exports\StoreReportExport;
use Maatwebsite\Excel\Facades\Excel;

class StoreAuditLogReport extends Report {
    
    public function index(Request $request) {

        $filters = [
            'name'=>$request->name,
            'store'=>$request->store,
            'start_day'=>$request->start_day,
            'end_day'=>$request->end_day,
            'data'=>$request->data
        ];

        $data = $request->data;
        $list = app(AuditRoute::class)->getListByFilters([], $filters);

        return view('reports.store_report', compact(
            'list', 'filters', 'data'
        ));
    }

    public function downloadExcel(Request $request) {
        $filters = [
            'name'=>$request->name,
            'store'=>$request->store,
            'start_day'=>$request->start_day,
            'end_day'=>$request->end_day,
        ];

        $list = app(AuditRoute::class)->getAllDataByFilters([], $filters);

        return Excel::download(new StoreReportExport($list), date('Ymd').'_餐飲業者評核紀錄一覽表.xlsx');
    }
}
