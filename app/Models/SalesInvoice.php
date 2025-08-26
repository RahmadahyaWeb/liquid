<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    protected $guarded = [];

    public static function generateNomorInvoice(): string
    {
        $prefix = 'INV-' . now()->format('Ym'); // contoh: INV-202508

        $last = self::where('no_invoice', 'LIKE', $prefix . '-%')
            ->orderByDesc('no_invoice')
            ->first();

        $lastNumber = 0;

        if ($last && preg_match('/' . $prefix . '-(\d+)/', $last->no_invoice, $matches)) {
            $lastNumber = (int) $matches[1];
        }

        $nextNumber = $lastNumber + 1;

        return $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public static function generateInvoice(SalesOrder $salesOrder)
    {
        self::validateWarehouseStock($salesOrder);

        $customer = $salesOrder->customer;
        $top = $customer->TOP ?? 0;
        $tanggal = now();
        $jatuhTempo = $top > 0 ? $tanggal->copy()->addDays($top) : $tanggal;

        $invoice = SalesInvoice::create([
            'no_invoice'        => self::generateNomorInvoice(),
            'tanggal'           => $tanggal,
            'jatuh_tempo'       => $jatuhTempo,
            'sales_order_id'    => $salesOrder->id,
            'customer_id'       => $salesOrder->customer_id,
            'warehouse_id'      => $salesOrder->warehouse_id,
            'metode_pembayaran' => $top == 0 ? 'tunai' : 'kredit',
            'total_harga'       => $salesOrder->total_harga,
            'total_diskon'      => $salesOrder->total_diskon,
            'total_bayar'       => $salesOrder->total_bayar,
            'keterangan'        => 'Generated from SO: ' . $salesOrder->no_so,
        ]);

        foreach ($salesOrder->details as $detail) {
            $invoiceDetail = SalesInvoiceDetail::create([
                'sales_invoice_id' => $invoice->id,
                'product_id'       => $detail->product_id,
                'qty'              => $detail->qty,
                'harga_jual'       => $detail->harga_jual,
                'diskon'           => $detail->diskon,
                'subtotal'         => $detail->subtotal,
            ]);

            $qtySisa = $detail->qty;
            $productId = $detail->product_id;
            $warehouseId = $salesOrder->warehouse_id;

            self::insertFifoUsage($productId, $warehouseId, $qtySisa, $invoiceDetail);

            WarehouseStock::decrementStock($productId, $warehouseId, $qtySisa);
        }

        $salesOrder->update([
            'status' => 'invoiced'
        ]);
    }

    protected static function insertFifoUsage($productId, $warehouseId, $qtySisa, $invoiceDetail)
    {
        $fifoLayers = FifoLayer::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->where('qty_sisa', '>', 0)
            ->orderBy('tanggal_masuk')
            ->lockForUpdate()
            ->get();

        foreach ($fifoLayers as $layer) {
            if ($qtySisa <= 0) break;

            $qtyTerpakai = min($layer->qty_sisa, $qtySisa);

            FifoUsage::create([
                'sales_invoice_detail_id' => $invoiceDetail->id,
                'fifo_layer_id'           => $layer->id,
                'qty_used'                => $qtyTerpakai,
                'harga_modal_per_unit'    => $layer->harga_modal,
                'total_harga_modal'       => $qtyTerpakai * $layer->harga_modal,
            ]);

            $layer->decrement('qty_sisa', $qtyTerpakai);
            $qtySisa -= $qtyTerpakai;
        }
    }

    protected static function validateWarehouseStock($salesOrder)
    {
        foreach ($salesOrder->details as $detail) {
            $stokGudang = WarehouseStock::where('product_id', $detail->product_id)
                ->where('warehouse_id', $salesOrder->warehouse_id)
                ->first();

            $stokTersedia = $stokGudang?->qty_stok ?? 0;

            if ($stokTersedia < $detail->qty) {
                throw new \Exception("Stok tidak cukup untuk produk {$detail->product->kode_produk} (butuh {$detail->qty}, tersedia {$stokTersedia})");
            }
        }
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function details()
    {
        return $this->hasMany(SalesInvoiceDetail::class);
    }
}
