<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReceiptDetail extends Model
{
    protected $guarded = [];

    public function receipt()
    {
        return $this->belongsTo(PurchaseReceipt::class, 'purchase_receipt_id');
    }

    public function purchaseDetail()
    {
        return $this->belongsTo(PurchaseDetail::class);
    }
}
