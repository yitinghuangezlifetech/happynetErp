<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BonusSummaryExport implements FromView
{
    protected $list;

    public function __construct($list)
    {
        $this->list = $list;
    }

    public function view(): View
    {

        return view('exports.bonus_sammary', [
            'list' => $this->list
        ]);
    }
}
