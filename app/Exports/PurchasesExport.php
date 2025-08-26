<?php

namespace App\Exports;

use App\Models\PurchaseDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchasesExport implements FromCollection, WithHeadings
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
        $results = PurchaseDetail::query()
            ->with(['receiptDetails', 'purchase', 'product'])
            ->join('purchases', 'purchases.id', '=', 'purchase_details.purchase_id')
            ->whereBetween('purchases.tanggal_pembelian', [$this->fromDate, $this->toDate])
            ->orderBy('purchases.nomor_pembelian')
            ->get();

        return $results->map(function ($result) {
            return [
                'No Pembelian' => $result->purchase->nomor_pembelian,
                'Nama Supplier' => $result->purchase->supplier->nama_supplier,
                'Nama Gudang' => $result->purchase->warehouse->nama_gudang,
                'Tanggal Pembelian' => $result->purchase->tanggal_pembelian,
                'Status' => $result->purchase->status,
                'Kode Produk' => $result->product->kode_produk,
                'Qty' => $result->qty,
                'Harga' => $result->harga_modal,
                'Subtotal' => $result->subtotal
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No Pembelian',
            'Nama Supplier',
            'Nama Gudang',
            'Tanggal Pembelian',
            'Status',
            'Kode Produk',
            'Qty',
            'Harga',
            'Subtotal'
        ];
    }
}
