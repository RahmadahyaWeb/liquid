<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\WarehouseStock as ModelsWarehouseStock;

class WarehouseStock extends BaseComponent
{
    public function render()
    {
        $rows = ModelsWarehouseStock::with(['product', 'warehouse'])
            ->paginate();

        $columns = [
            'product.nama_produk' => 'Nama Produk',
            'warehouse.nama_gudang' => 'Nama Gudang',
            'qty_stok' => 'Qty'
        ];

        $canDelete = fn($row) => false;
        $canEdit = fn($row) => false;

        return view('livewire.menu.warehouse-stock', compact(
            'rows',
            'columns',
            'canEdit',
            'canDelete'
        ));
    }
}
