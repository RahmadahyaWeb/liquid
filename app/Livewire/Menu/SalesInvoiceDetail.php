<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\Config;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceDetail as ModelsSalesInvoiceDetail;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesInvoiceDetail extends BaseComponent
{
    public $id;

    public function mount($id)
    {
        $this->id = $id;
    }

    public function print()
    {
        $invoiceHeader = SalesInvoice::findOrFail($this->id);
        $invoiceDetails = ModelsSalesInvoiceDetail::where('sales_invoice_id', $this->id)
            ->get();

        $config = Config::pluck('value', 'config');

        $no_invoice = $invoiceHeader->no_invoice;
        $filename = "invoice_" . $no_invoice . ".pdf";

        $data = [
            'header' => $invoiceHeader,
            'items' => $invoiceDetails,
            'config' => $config
        ];

        $pdf = Pdf::loadView('print.invoice', $data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, $filename);
    }

    public function render()
    {
        $rows = ModelsSalesInvoiceDetail::with(['invoice', 'product'])
            ->where('sales_invoice_id', $this->id)
            ->paginate();

        $columns = [
            'invoice.no_invoice' => 'No Invoice',
            'invoice.salesOrder.no_so' => 'No SO',
            'product.kode_produk' => 'Kode Produk',
            'product.nama_produk' => 'Nama Produk',
            'qty' => 'Qty',
            'harga_jual' => 'Harga Jual',
            'diskon' => 'Diskon',
            'subtotal' => 'Subtotal',
        ];

        $columnFormats = [
            'harga_jual' => fn($row) => $this->format_rupiah($row->harga_jual),
            'diskon' => fn($row) => $this->format_rupiah($row->diskon),
            'subtotal' => fn($row) => $this->format_rupiah($row->subtotal),
        ];

        $cellClass = function ($row, $field) {
            $columnsWithClass = [
                'no_invoice',
                'invoice.salesOrder.no_so',
                'harga_jual',
                'diskon',
                'subtotal',
                'product.kode_produk'
            ];

            if (in_array($field, $columnsWithClass)) {
                return 'whitespace-nowrap';
            }
            return '';
        };

        $canDelete = fn($row) => false;
        $canEdit = fn($row) => false;

        return view('livewire.menu.sales-invoice-detail', compact(
            'rows',
            'columns',
            'columnFormats',
            'cellClass',
            'canDelete',
            'canEdit'
        ));
    }
}
