<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;

interface ReportInterface {

    public function index(Request $request);
    
    public function downloadExcel(Request $request);
}