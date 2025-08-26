<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReceipt extends Model
{
    protected $guarded = [];

    public static function generateNomorPenerimaan(): string
    {
        $prefix = 'POR-' . now()->format('Ym'); // contoh: POR-202508

        $last = self::where('kode_penerimaan', 'LIKE', $prefix . '-%')
            ->orderByDesc('kode_penerimaan')
            ->first();

        $lastNumber = 0;

        if ($last && preg_match('/' . $prefix . '-(\d+)/', $last->kode_penerimaan, $matches)) {
            $lastNumber = (int) $matches[1];
        }

        $nextNumber = $lastNumber + 1;

        return $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public static function insertFifoLayer($receiptDetails, $warehouseId, $tanggalPenerimaan)
    {
        foreach ($receiptDetails as $detail) {
            $purchaseDetail = $detail->purchaseDetail;

            $productId = $purchaseDetail->product_id;
            $warehouseId = $warehouseId;
            $qtyDiterima = $detail->qty_diterima;
            $hargaModal = $purchaseDetail->harga_modal;

            FifoLayer::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'tanggal_masuk' => $tanggalPenerimaan,
                'qty_total' => $qtyDiterima,
                'qty_sisa' => $qtyDiterima,
                'harga_modal' => $hargaModal,
                'source' => 'PEMBELIAN',
                'purchase_detail_id' => $purchaseDetail->id,
            ]);

            WarehouseStock::incrementStock($productId, $warehouseId, $qtyDiterima);
        }
    }

    public static function rollback($receiptDetails, $warehouseId)
    {
        foreach ($receiptDetails as $detail) {
            $purchaseDetail = $detail->purchaseDetail;

            $productId = $purchaseDetail->product_id;
            $warehouseId = $warehouseId;
            $qtyDiterima = $detail->qty_diterima;

            $fifoLayer = FifoLayer::where('source', 'PEMBELIAN')
                ->where('purchase_detail_id', $purchaseDetail->id)
                ->where('warehouse_id', $warehouseId)
                ->first();

            if ($fifoLayer->qty_sisa < $fifoLayer->qty_total) {
                throw new \Exception("FIFO layer sudah dipakai, tidak bisa dihapus.");
            }

            $fifoLayer->delete();

            WarehouseStock::decrementStock($productId, $warehouseId, $qtyDiterima);
        }
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function receiptDetails()
    {
        return $this->hasMany(PurchaseReceiptDetail::class);
    }
}
