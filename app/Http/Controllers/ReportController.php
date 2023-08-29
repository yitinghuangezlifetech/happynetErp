<?php

namespace App\Http\Controllers;

use Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{

    protected $report;

    public function __construct(Request $request)
    {
        $slug = explode('.', Route::currentRouteName());

        if (count($slug) > 1) {
            $class = explode('_', $slug[1]);

            if (count($class) > 1) {
                if (class_exists('App\Http\Controllers\Reports\\' . ucfirst($class[0]) . ucfirst($class[1]) . 'Report')) {
                    $this->report = app('App\Http\Controllers\Reports\\' . ucfirst($class[0]) . ucfirst($class[1]) . 'Report');
                }
            } else {
                if (class_exists('App\Http\Controllers\Reports\\' . ucfirst($class[0]) . 'Report')) {
                    $this->report = app('App\Http\Controllers\Reports\\' . ucfirst($class[0]) . 'Report');
                }
            }
        } else {
            if (class_exists('App\Http\Controllers\Reports\\' . ucfirst($slug[0]) . 'Report')) {
                $this->report = app('App\Http\Controllers\Reports\\' . ucfirst($slug[0]) . 'Report');
            }
        }
    }

    public function index(Request $request)
    {
        return $this->report->index($request);
    }


    public function download(Request $request)
    {
        return $this->report->downloadExcel($request);
    }

    public function show(Request $request, $id)
    {
        return $this->report->content($request, $id);
    }
}
