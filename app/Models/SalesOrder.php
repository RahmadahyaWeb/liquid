<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $guarded = [];

    public static function generateNomorSalesOrder(): string
    {
        $prefix = 'SO-' . now()->format('Ym'); // contoh: SO-202508

        $last = self::where('no_so', 'LIKE', $prefix . '-%')
            ->orderByDesc('no_so')
            ->first();

        $lastNumber = 0;

        if ($last && preg_match('/' . $prefix . '-(\d+)/', $last->no_so, $matches)) {
            $lastNumber = (int) $matches[1];
        }

        $nextNumber = $lastNumber + 1;

        return $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function details()
    {
        return $this->hasMany(SalesOrderDetails::class);
    }
}
