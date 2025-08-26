<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseStock extends Model
{
    protected $guarded = [];

    public static function incrementStock($productId, $warehouseId, $qtyDiterima)
    {
        $stock = WarehouseStock::firstOrCreate([
            'product_id' => $productId,
            'warehouse_id'  => $warehouseId,
        ]);

        $stock->increment('qty_stok', $qtyDiterima);
    }

    public static function decrementStock($productId, $warehouseId, $qtyDiterima)
    {
        $stock = WarehouseStock::firstOrCreate([
            'product_id' => $productId,
            'warehouse_id'  => $warehouseId,
        ]);

        $stock->decrement('qty_stok', $qtyDiterima);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
