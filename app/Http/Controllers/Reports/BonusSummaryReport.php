<?php

namespace App\Http\Controllers\Reports;

use Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Reports\Contracts\InterfaceReport;

use App\Models\DialRecord;
use App\Models\Organization;

use App\Exports\BonusSummaryExport;

class BonusSummaryReport implements InterfaceReport
{
    public function index(Request $request, $reportName)
    {
        $filters = [
            'rows' => $request->rows ?? 20,
            'start' => $request->start,
            'end' => $request->end,
            'organization_id' => $request->organization_id,
        ];

        $user = Auth::user();

        if ($user->role->super_admin != 1) {
            $filters['organizations'] = [];

            foreach ($user->organization->childs ?? [] as $child) {
                array_push($filters['organizations'], $child->id);
            }

            $organizations = app(Organization::class)
                ->whereIn('id', $filters['organizations'])
                ->where('id', '!=', $user->organization_id)
                ->get();
        } else {
            $organizations = app(Organization::class)
                ->where('id', '!=', $user->organization_id)
                ->get();
        }

        $list = app(DialRecord::class)->getBonusSummaryLogs($filters);

        return view('reports.bonus_summaries.index', compact(
            'filters',
            'organizations',
            'list',
            'reportName'
        ));
    }

    public function content(Request $request, $id)
    {
    }

    public function downloadExcel(Request $request)
    {
        $filters = [
            'rows' => $request->rows ?? 20,
            'start' => $request->start,
            'end' => $request->end,
            'organization_id' => $request->organization_id,
        ];

        $user = Auth::user();

        if ($user->role->super_admin != 1) {
            $filters['organizations'] = [];

            foreach ($user->organization->childs ?? [] as $child) {
                array_push($filters['organizations'], $child->id);
            }
        }

        $list = app(DialRecord::class)->getBonusSummaryAllLogs($filters);

        return Excel::download(new BonusSummaryExport($list), date('Ymd') . '_奬金總表.xlsx');
    }
}
