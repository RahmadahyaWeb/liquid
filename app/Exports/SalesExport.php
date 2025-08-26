<?php

namespace App\Exports;

use App\Models\SalesInvoiceDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    protected $fromDate;
    protected $toDate;

    public function __construct($fromDate, $toDate)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function collection()
    {
        $results = SalesInvoiceDetail::query()
            ->join('sales_invoices', 'sales_invoices.id', '=', 'sales_invoice_details.sales_invoice_id')
            ->with(['invoice', 'product'])
            ->whereBetween('sales_invoices.tanggal', [$this->fromDate, $this->toDate])
            ->orderBy('sales_invoices.no_invoice')
            ->get();

        return $results->map(function ($result) {
            return [
                'No Invoice' => $result->invoice->no_invoice,
                'No SO' => $result->invoice->salesOrder->no_so,
                'Kode Pelanggan' => $result->invoice->customer->kode_pelanggan,
                'Nama Pelanggan' => $result->invoice->customer->nama_pelanggan,
                'Gudang' => $result->invoice->warehouse->nama_gudang,
                'Qty' => $result->qty,
                'Harga Jual' => $result->harga_jual,
                'Diskon' => $result->diskon,
                'Subtotal' => $result->subtotal
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No Invoice',
            'No SO',
            'Kode Pelanggan',
            'Nama Pelanggan',
            'Gudang',
            'Qty',
            'Harga Jual',
            'Diskon',
            'Subtotal'
        ];
    }
}
