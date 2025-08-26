<?php

namespace App\Livewire\Menu\Report;

use App\Exports\ArExport;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ReportAr extends Component
{
    public $from_date, $to_date, $status;

    public function download()
    {
        return Excel::download(new ArExport($this->from_date, $this->to_date, $this->status), 'laporan_ar.xlsx');
    }

    public function render()
    {
        return view('livewire.menu.report.report-ar');
    }
}
