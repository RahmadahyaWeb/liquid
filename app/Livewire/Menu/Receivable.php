<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\Receivable as ModelsReceivable;

class Receivable extends BaseComponent
{
    public function render()
    {
        $rows = ModelsReceivable::with(['customer', 'invoice', 'payments'])
            ->withSum('payments', 'amount')
            ->paginate();

        foreach ($rows as $row) {
            $row->recalcBalance();
        }

        $columns = [
            'invoice.no_invoice' => 'No Invoice',
            'customer.kode_pelanggan' => 'Kode Pelanggan',
            'customer.nama_pelanggan' => 'Nama Pelanggan',
            'amount' => 'Total Piutang',
            'payments_sum_amount' => 'Total Pembayaran',
            'balance' => 'Sisa Piutang',
            'due_date' => 'Tanggal Jatuh Tempo',
            'status' => 'Status'
        ];

        $columnFormats = [
            'amount' => fn($row) => $this->format_rupiah($row->amount),
            'balance' => fn($row) => $this->format_rupiah($row->balance),
            'payments_sum_amount' => fn($row) => $this->format_rupiah($row->payments_sum_amount),
        ];

        $cellClass = function ($row, $field) {
            $columnsWithClass = [
                'invoice.no_invoice',
                'amount',
                'balance',
                'payments_sum_amount'
            ];

            if (in_array($field, $columnsWithClass)) {
                return 'whitespace-nowrap';
            }
            return '';
        };

        $canDelete = fn($row) => false;
        $canEdit = fn($row) => false;

        return view('livewire.menu.receivable', compact(
            'rows',
            'columns',
            'canDelete',
            'canEdit',
            'columnFormats',
            'cellClass'
        ));
    }
}
