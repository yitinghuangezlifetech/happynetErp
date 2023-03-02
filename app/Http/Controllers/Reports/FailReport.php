<?php

namespace App\Http\Controllers\Reports;

use PDF;
use File;
use App\Models\AuditRoute;
use Illuminate\Http\Request;

class FailReport extends Report {

    public function index(Request $request) {

        $filters = [
            'name'=>$request->name,
            'store'=>$request->store,
            'start_day'=>$request->start_day,
            'end_day'=>$request->end_day,
            'audit_status'=>1,
            'status'=>4,
            'data'=>$request->data
        ];

        $data = $request->data;
        $list = app(AuditRoute::class)->getListByFilters([], $filters);

        return view('reports.fail_report', compact(
            'list', 'filters', 'data'
        ));
    }

    public function downloadExcel(Request $request) {
        ini_set('memory_limit', -1);
        $path = storage_path('app/public').'/pdfs';
        $filePath = 'pdfs/'.date('Ymd').'_商家缺失報告.pdf';

        File::makeDirectory($path, $mode = 0777, true, true);
        PDF::setOptions(['defaultFont' => 'ARIALUNI', 'fontDir'=>storage_path('app/public/fonts')]);

        $route = app(AuditRoute::class)->find($request->id);

        $pdf = PDF::loadView('exports.fail_details', compact('route'));
        $pdf->save(storage_path('app/public').'/'.$filePath, 'UTF-8');
        return $pdf->download(date('Ymd').'_商家缺失報告.pdf');
    }
}
