<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class Report extends Controller implements ReportInterface {
    
    abstract function index(Request $request);

    abstract function downloadExcel(Request $request);
}
