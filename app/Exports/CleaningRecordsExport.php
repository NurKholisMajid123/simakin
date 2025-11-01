<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CleaningRecordsExport implements FromView, WithTitle, ShouldAutoSize
{
    protected $ruangans;
    protected $startDate;
    protected $endDate;

    public function __construct($ruangans, $startDate, $endDate)
    {
        $this->ruangans = $ruangans;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        return view('admin.cleaning_records.export', [
            'ruangans' => $this->ruangans,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }

    public function title(): string
    {
        return 'Laporan Kebersihan';
    }
}