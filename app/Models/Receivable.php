<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{
    protected $guarded = [];


    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // hitung ulang balance dari payments
    public function recalcBalance(): void
    {
        $paid = $this->payments()->sum('amount');
        $this->balance = $this->amount - $paid;

        if ($this->balance <= 0) {
            $this->status = 'PAID';
            $this->balance = 0;
        } elseif ($paid > 0) {
            $this->status = 'PARTIAL';
        } elseif (now()->greaterThan($this->due_date)) {
            $this->status = 'OVERDUE';
        } else {
            $this->status = 'OPEN';
        }

        $this->save();
    }
}
