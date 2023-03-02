<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AuditRouteExport implements FromView {

    protected $list;

    public function __construct($list) {
        $this->list = $list;
    }

    public function view(): View {

        return view('exports.audit_route', [
            'list' => $this->list
        ]);
    }
}
