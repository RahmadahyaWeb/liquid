<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\FifoLayer as ModelsFifoLayer;

class FifoLayer extends BaseComponent
{
    public function render()
    {
        $rows = ModelsFifoLayer::with(['product', 'warehouse', 'purchaseDetail'])->paginate();

        $columns = [
            'product.kode_produk' => 'Kode Produk',
            'product.nama_produk' => 'Nama Produk',
            'warehouse.nama_gudang' => 'Nama Gudang',
            'purchaseDetail.purchase.nomor_pembelian' => 'Nomor Pembelian',
            'qty_total' => 'Total Qty',
            'qty_sisa' => 'Sisa Qty',
            'harga_modal' => 'Harga Modal',
            'tanggal_masuk' => 'Tanggal',
            'source' => 'Source',
            'status' => 'Status'
        ];

        $columnFormats = [
            'harga_modal' => fn($row) => $this->format_rupiah($row->harga_modal),
        ];

        $cellClass = function ($row, $field) {
            $columnsWithClass = ['warehouse.nama_gudang', 'product.kode_produk', 'tanggal_masuk', 'harga_modal'];

            if (in_array($field, $columnsWithClass)) {
                return 'whitespace-nowrap';
            }
            return '';
        };

        $canDelete = fn($row) => false;
        $canEdit = fn($row) => false;

        return view('livewire.menu.fifo-layer', compact(
            'rows',
            'columns',
            'columnFormats',
            'cellClass',
            'canDelete',
            'canEdit'
        ));
    }
}
