<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesInvoiceDetail extends Model
{
    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // public function usages()
    // {
    //     return $this->hasMany(FifoUsage::class, 'sale_detail_id'); // dipakai di FIFO
    // }
}
