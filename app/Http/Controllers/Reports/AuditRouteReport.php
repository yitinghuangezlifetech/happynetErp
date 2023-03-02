<?php

namespace App\Http\Controllers\Reports;

use App\Models\AuditRoute;
use Illuminate\Http\Request;
use App\Exports\AuditRouteExport;
use Maatwebsite\Excel\Facades\Excel;

class AuditRouteReport extends Report {
    
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

        return view('reports.audit_route_report', compact(
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

        return Excel::download(new AuditRouteExport($list), date('Ymd').'_稽核行程報表.xlsx');
    }
}
