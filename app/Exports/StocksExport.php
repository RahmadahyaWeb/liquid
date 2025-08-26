<?php

namespace App\Exports;

use App\Models\WarehouseStock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StocksExport implements FromCollection, WithHeadings
{
    protected $warehouseId;

    public function __construct($warehouseId = null)
    {
        $this->warehouseId = $warehouseId;
    }

    public function collection()
    {
        $query = WarehouseStock::with(['product', 'warehouse']);

        if ($this->warehouseId) {
            $query->where('warehouse_id', $this->warehouseId);
        }

        $warehouses = $query->get()->sortBy(function ($item) {
            return $item->product->kode_produk;
        });

        return $warehouses->map(function ($warehouse) {
            return [
                'Kode Gudang'   => $warehouse->warehouse_id,
                'Nama Gudang'   => $warehouse->warehouse->nama_gudang,
                'Kode Produk'   => $warehouse->product->kode_produk,
                'Nama Produk'   => $warehouse->product->nama_produk,
                'Stok'          => $warehouse->qty_stok
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Kode Gudang',
            'Nama Gudang',
            'Kode Produk',
            'Nama Produk',
            'Stok',
        ];
    }
}
