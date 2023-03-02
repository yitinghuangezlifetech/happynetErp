<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends BasicController {
    
    protected $report;

    public function __construct(Request $request) {
        parent::__construct($request);

    	if (class_exists('App\Http\Controllers\Reports\\'.$request->data.'Report')) {
            $this->report = app('App\Http\Controllers\Reports\\'.$request->data.'Report');
        }
    }

    public function index(Request $request) {
    	return $this->report->index($request);
    }

    public function download(Request $request) {
        return $this->report->downloadExcel($request);
    }

    public function show(Request $request, $id) {
        return $this->report->details($request, $id);
    }
}
