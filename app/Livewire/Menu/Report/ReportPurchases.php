<?php

namespace App\Livewire\Menu\Report;

use App\Exports\PurchasesExport;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ReportPurchases extends Component
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

        return Excel::download(new PurchasesExport($fromDate, $toDate), 'laporan_pembelian.xlsx');
    }

    public function render()
    {
        return view('livewire.menu.report.report-purchases');
    }
}
