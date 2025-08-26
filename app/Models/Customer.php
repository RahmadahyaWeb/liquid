<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public static function generateKodePelanggan(): string
    {
        $last = self::where('kode_pelanggan', 'LIKE', 'CS-%')
            ->orderByDesc('kode_pelanggan')
            ->first();

        $lastNumber = 0;

        if ($last && preg_match('/CS-(\d+)/', $last->kode_pelanggan, $matches)) {
            $lastNumber = (int) $matches[1];
        }

        $nextNumber = $lastNumber + 1;

        return 'CS-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
