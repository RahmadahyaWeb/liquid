<?php

namespace App\Livewire\Menu\Report;

use App\Exports\StocksExport;
use App\Models\Warehouse;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ReportStocks extends Component
{
    public $warehouseId;
    public $warehouses;

    public function mount()
    {
        $this->fetchWarehouses();
    }

    public function fetchWarehouses()
    {
        $this->warehouses = Warehouse::all();
    }

    public function download()
    {
        return Excel::download(new StocksExport($this->warehouseId), 'laporan_stok.xlsx');
    }

    public function render()
    {
        return view('livewire.menu.report.report-stocks');
    }
}
