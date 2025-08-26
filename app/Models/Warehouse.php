<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $guarded = [];

    public function warehouseStocks()
    {
        return $this->hasMany(WarehouseStock::class, 'gudang_id');
    }

    public function purchaseReceipts()
    {
        return $this->hasMany(PurchaseReceipt::class, 'warehouse_id');
    }
}
