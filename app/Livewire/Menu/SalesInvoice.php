<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\SalesInvoice as ModelsSalesInvoice;

class SalesInvoice extends BaseComponent
{
    public function render()
    {
        $rows = ModelsSalesInvoice::with(['customer', 'warehouse', 'salesOrder', 'details'])
            ->orderBy('no_invoice', 'desc')
            ->paginate();

        $columns = [
            'no_invoice' => 'No Invoice',
            'salesOrder.no_so' => 'No SO',
            'customer.kode_pelanggan' => 'Kode Pelanggan',
            'customer.nama_pelanggan' => 'Nama Pelanggan',
            'warehouse.nama_gudang' => 'Gudang',
            'tanggal' => 'Tanggal',
            'jatuh_tempo' => 'Jatuh Tempo',
            'metode_pembayaran' => 'Metode Pembayaran',
            'total_harga' => 'Total Harga',
            'total_diskon' => 'Total Diskon',
            'total_bayar' => 'Total Bayar',
            'keterangan' => 'Keterangan',
        ];

        $columnFormats = [
            'total_harga' => fn($row) => $this->format_rupiah($row->total_harga),
            'total_diskon' => fn($row) => $this->format_rupiah($row->total_diskon),
            'total_bayar' => fn($row) => $this->format_rupiah($row->total_bayar),
        ];

        $cellClass = function ($row, $field) {
            $columnsWithClass = [
                'no_invoice',
                'salesOrder.no_so',
                'customer.kode_pelanggan',
                'tanggal',
                'jatuh_tempo',
                'warehouse.nama_gudang',
                'total_harga',
                'total_diskon',
                'total_bayar',
                'keterangan'
            ];

            if (in_array($field, $columnsWithClass)) {
                return 'whitespace-nowrap';
            }
            return '';
        };

        $canDelete = fn($row) => false;
        $canEdit = fn($row) => false;

        return view('livewire.menu.sales-invoice', compact(
            'rows',
            'columns',
            'columnFormats',
            'cellClass',
            'canEdit',
            'canDelete'
        ));
    }
}
