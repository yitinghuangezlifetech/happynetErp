<?php

namespace App\Http\Controllers;

use Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends BasicController
{

    protected $report;

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $class = explode('_', $request->reportName);

        if (count($class) > 1) {
            if (class_exists('App\Http\Controllers\Reports\\' . ucfirst($class[0]) . ucfirst($class[1]) . 'Report')) {
                $this->report = app('App\Http\Controllers\Reports\\' . ucfirst($class[0]) . ucfirst($class[1]) . 'Report');
            }
        } else {
            if (class_exists('App\Http\Controllers\Reports\\' . ucfirst($class[0]) . 'Report')) {
                $this->report = app('App\Http\Controllers\Reports\\' . ucfirst($class[0]) . 'Report');
            }
        }
    }

    public function reportIndex(Request $request, $reportName)
    {
        return $this->report->index($request, $reportName);
    }


    public function download(Request $request, $reportName)
    {
        return $this->report->downloadExcel($request, $reportName);
    }

    public function show(Request $request, $id)
    {
        return $this->report->content($request, $id);
    }
}
