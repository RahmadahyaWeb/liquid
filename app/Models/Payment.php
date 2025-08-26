<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];


    public function receivable()
    {
        return $this->belongsTo(Receivable::class);
    }

    protected static function booted()
    {
        // setiap kali ada payment baru, balance receivable dihitung ulang
        static::created(function ($payment) {
            $payment->receivable->recalcBalance();
        });

        static::deleted(function ($payment) {
            $payment->receivable->recalcBalance();
        });
    }
}
