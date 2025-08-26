<?php

namespace App\Livewire\Menu\Report;

use App\Exports\ProductsExport;
use App\Models\ProductCategory;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ReportProducts extends Component
{
    public $categoryId;
    public $categories;

    public function mount()
    {
        $this->fetchCategories();
    }

    public function fetchCategories()
    {
        $this->categories = ProductCategory::all();
    }

    public function download()
    {
        return Excel::download(new ProductsExport($this->categoryId), 'laporan_produk.xlsx');
    }

    public function render()
    {
        return view('livewire.menu.report.report-products');
    }
}
