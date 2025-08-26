<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FifoUsage extends Model
{
    protected $guarded = [];

    public function fifoLayer()
    {
        return $this->belongsTo(FifoLayer::class, 'fifo_layer_id');
    }

    public function salesInvoiceDetail()
    {
        return $this->belongsTo(SalesInvoiceDetail::class, 'sales_invoice_detail_id');
    }
}
