<?php

namespace App\Http\Controllers\Reports\Contracts;

use Illuminate\Http\Request;

interface InterfaceReport
{
    public function index(Request $request, $reportName);

    public function content(Request $request, $id);

    public function downloadExcel(Request $request);
}
