<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = [];

    public static function generateNomorPembelian(): string
    {
        $prefix = 'PO-' . now()->format('Ym'); // contoh: PO-202508

        $last = self::where('nomor_pembelian', 'LIKE', $prefix . '-%')
            ->orderByDesc('nomor_pembelian')
            ->first();

        $lastNumber = 0;

        if ($last && preg_match('/' . $prefix . '-(\d+)/', $last->nomor_pembelian, $matches)) {
            $lastNumber = (int) $matches[1];
        }

        $nextNumber = $lastNumber + 1;

        return $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function receipts()
    {
        return $this->hasMany(PurchaseReceipt::class, 'purchase_id');
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
