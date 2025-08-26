<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FifoExport implements FromCollection, WithHeadings
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $results = DB::table('fifo_usages')
            ->join('fifo_layers', 'fifo_usages.fifo_layer_id', '=', 'fifo_layers.id')
            ->leftJoin('purchase_details', 'fifo_layers.purchase_detail_id', '=', 'purchase_details.id')
            ->leftJoin('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
            ->join('sales_invoice_details', 'fifo_usages.sales_invoice_detail_id', '=', 'sales_invoice_details.id')
            ->join('sales_invoices', 'sales_invoice_details.sales_invoice_id', '=', 'sales_invoices.id')
            ->join('products', 'sales_invoice_details.product_id', '=', 'products.id')
            ->select(
                'sales_invoices.no_invoice',
                'sales_invoices.tanggal as tanggal_jual',
                'products.kode_produk',
                'products.nama_produk',
                'fifo_layers.tanggal_masuk as tanggal_stok_masuk',
                'fifo_layers.source as sumber_stok',
                'fifo_usages.qty_used',
                'fifo_usages.harga_modal_per_unit',
                'fifo_usages.total_harga_modal',
                'sales_invoice_details.harga_jual',
                DB::raw('(fifo_usages.qty_used * sales_invoice_details.harga_jual) as total_penjualan'),
                DB::raw('((fifo_usages.qty_used * sales_invoice_details.harga_jual) - fifo_usages.total_harga_modal) as profit'),
                'purchases.nomor_pembelian',
                'purchases.tanggal_pembelian as tanggal_pembelian'
            )
            ->when($this->bulan && $this->tahun, function ($query) {
                $query->whereMonth('sales_invoices.tanggal', $this->bulan)
                    ->whereYear('sales_invoices.tanggal', $this->tahun);
            })
            ->orderBy('sales_invoices.tanggal')
            ->orderBy('sales_invoices.no_invoice')
            ->get();

        return $results;
    }

    public function headings(): array
    {
        return [
            'No Invoice',
            'Tanggal Jual',
            'Kode Produk',
            'Nama Produk',
            'Tanggal Stok Masuk',
            'Sumber Stok',
            'Qty Digunakan',
            'Harga Modal per Unit',
            'Total Harga Modal',
            'Harga Jual',
            'Total Penjualan',
            'Profit',
            'No Pembelian',
            'Tanggal Pembelian',
        ];
    }
}
