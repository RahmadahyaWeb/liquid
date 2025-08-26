<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\FifoUsage as ModelsFifoUsage;

class FifoUsage extends BaseComponent
{
    public function render()
    {
        $rows = ModelsFifoUsage::with(['fifoLayer', 'salesInvoiceDetail'])
            ->paginate();

        $columns = [
            'fifo_layer_id' => 'Fifo Layer',
            'salesInvoiceDetail.invoice.no_invoice' => 'No Invoice',
            'salesInvoiceDetail.id' => 'Invoice Detail',
            'qty_used' => 'Qty Terpakai',
            'harga_modal_per_unit' => 'Harga Modal per Unit',
            'total_harga_modal' => 'Total Harga Modal'
        ];

        $columnFormats = [
            'harga_modal_per_unit' => fn($row) => $this->format_rupiah($row->harga_modal_per_unit),
            'total_harga_modal' => fn($row) => $this->format_rupiah($row->total_harga_modal),
        ];

        $canDelete = fn($row) => false;
        $canEdit = fn($row) => false;

        return view('livewire.menu.fifo-usage', compact([
            'rows',
            'columns',
            'columnFormats',
            'canDelete',
            'canEdit'
        ]));
    }
}
