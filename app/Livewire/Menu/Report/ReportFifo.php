<?php

namespace App\Livewire\Menu\Report;

use App\Exports\FifoExport;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ReportFifo extends Component
{
    public $month;
    public $bulan, $tahun;

    public function updatedMonth($value)
    {
        $parts = explode('-', $value);
        $this->tahun = $parts[0] ?? null;
        $this->bulan = $parts[1] ?? null;
    }

    public function download()
    {
        $this->validate([
            'month' => 'required'
        ]);

        return Excel::download(new FifoExport($this->bulan, $this->tahun), 'laporan_fifo.xlsx');
    }

    public function render()
    {
        return view('livewire.menu.report.report-fifo');
    }
}
