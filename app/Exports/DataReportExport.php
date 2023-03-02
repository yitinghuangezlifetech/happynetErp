<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DataReportExport implements FromView {

    protected $list;

    public function __construct($list) {
        $this->list = $list;
    }

    public function view(): View {

        return view('exports.data_report', [
            'list' => $this->list
        ]);
    }
}
