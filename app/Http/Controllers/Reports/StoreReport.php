<?php

namespace App\Http\Controllers\Reports;

use App\Models\Store;
use App\Models\StoreType;
use Illuminate\Http\Request;
use App\Exports\StoreExport;
use Maatwebsite\Excel\Facades\Excel;

class StoreReport extends Report {
    public function index(Request $request) {

        $filters = [
            'name'=>$request->name,
            'store_type_id'=>$request->store_type_id,
            'data'=>$request->data
        ];

        $storeTypes = app(StoreType::class)->get();
        $data = $request->data;
        $list = app(Store::class)->getDataByFilters($filters);

        return view('reports.store_list', compact(
            'list', 'filters', 'data', 'storeTypes'
        ));
    }

    public function downloadExcel(Request $request) {
        $filters = [
            'name'=>$request->name,
            'store_type_id'=>$request->store_type_id,
            'data'=>$request->data
        ];

        $list = app(Store::class)->getAllDataByFilters([], $filters);

        return Excel::download(new StoreExport($list), date('Ymd').'_商家一覽表.xlsx');
    }
}
