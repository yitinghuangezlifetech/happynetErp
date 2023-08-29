<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Reports\Contracts\InterfaceReport;

class BonusSummaryReport implements InterfaceReport
{
    public function index(Request $request)
    {
        return view('reports.bonus_summaries.index');
    }

    public function content(Request $request, $id)
    {
    }

    public function downloadExcel(Request $request)
    {
    }
}
