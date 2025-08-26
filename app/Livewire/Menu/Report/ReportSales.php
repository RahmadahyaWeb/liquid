<?php

namespace App\Livewire\Menu\Report;

use App\Exports\SalesExport;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ReportSales extends Component
{
    public $fromDate, $toDate;

    public function rules()
    {
        return [
            'fromDate' => 'required',
            'toDate' => 'required',
        ];
    }

    public function download()
    {
        $this->validate();

        $fromDate = Carbon::parse($this->fromDate)->startOfDay();
        $toDate = Carbon::parse($this->toDate)->startOfDay();

        return Excel::download(new SalesExport($fromDate, $toDate), 'laporan_penjualan.xlsx');
    }

    public function render()
    {
        return view('livewire.menu.report.report-sales');
    }
}
