<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\PurchaseReceipt;
use App\Models\PurchaseReceiptDetail as ModelsPurchaseReceiptDetail;
use App\Models\WarehouseStock;
use Exception;
use Illuminate\Support\Facades\DB;

class PurchaseReceiptDetail extends BaseComponent
{
    public $modalTitle = 'Form Detail Penerimaan';

    protected array $permissionMap = [
        'save' => ['edit receipt-detail'],
        'edit' => ['edit receipt-detail'],
        'delete' => ['delete receipt-detail']
    ];

    public $editing =  [
        'id' => '',
        'purchase_detail_id' => '',
        'qty_diterima' => '',
        'nama_produk' => '',
        'kode_produk' => '',
        'qty' => ''
    ];

    public $id;
    public $receiptHeader;

    public function mount($id)
    {
        $this->id = $id;

        $this->receiptHeader = PurchaseReceipt::findOrFail($this->id);
    }

    public function rules()
    {
        return [
            'editing.qty_diterima' => 'required'
        ];
    }

    public function edit($id)
    {
        $this->editRecord($id, [
            'model' => PurchaseDetail::class,
            'with' => ['purchase', 'receiptDetails', 'product'],
            'map' => function ($data) {
                return [
                    'id' => $data->id,
                    'kode_produk' => $data->product->kode_produk,
                    'nama_produk' => $data->product->nama_produk,
                    'qty' => $data->qty,
                    'qty_diterima' => $data->receiptDetails->qty_diterima ?? 0,
                ];
            }
        ]);
    }

    public function save()
    {
        $this->validate();

        $this->executeSave(function () {
            $this->validateQtyDiterima();

            ModelsPurchaseReceiptDetail::updateOrCreate(
                [
                    'purchase_receipt_id' => $this->id,
                    'purchase_detail_id' => $this->editing['id'],
                ],
                [
                    'qty_diterima' => $this->editing['qty_diterima']
                ]
            );
        });
    }

    public function validateQtyDiterima()
    {
        if (intval($this->editing['qty_diterima']) > $this->editing['qty']) {
            $this->addError('editing.qty_diterima', 'Qty Diterima tidak boleh lebih dari Qty Beli.');

            throw new Exception('Qty Diterima tidak boleh lebih dari Qty Beli.');
        }
    }

    public function updateReceiptStatus()
    {
        DB::beginTransaction();

        try {
            $receiptDetails = ModelsPurchaseReceiptDetail::with('purchaseDetail')
                ->where('purchase_receipt_id', $this->id)
                ->lockForUpdate()
                ->get();

            if ($receiptDetails->isEmpty()) {
                throw new Exception("Tidak ada barang yang diterima.");
            }

            $this->validateReceiptDetails($receiptDetails);
            PurchaseReceipt::insertFifoLayer($receiptDetails, $this->receiptHeader->warehouse_id, $this->receiptHeader->tanggal_penerimaan);

            Purchase::where('id', $this->receiptHeader->purchase_id)
                ->update([
                    'status' => 'received'
                ]);

            DB::commit();

            $this->showAlert('Proses Penerimaan Selesai');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->showAlert($e->getMessage(), 'danger', 'Error');
        }
    }

    public function validateReceiptDetails($receiptDetails)
    {
        foreach ($receiptDetails as $detail) {
            $expectedQty = $detail->purchaseDetail->qty ?? 0;

            if ($detail->qty_diterima != $expectedQty) {
                throw new Exception("Qty diterima untuk item dengan ID {$detail->purchase_detail_id} tidak sama dengan qty pembelian ({$expectedQty}).");
            }
        }
    }

    public function rollback()
    {
        DB::beginTransaction();

        try {
            $receiptDetails = ModelsPurchaseReceiptDetail::with('purchaseDetail')
                ->where('purchase_receipt_id', $this->id)
                ->lockForUpdate()
                ->get();

            PurchaseReceipt::rollback($receiptDetails, $this->receiptHeader->warehouse_id);

            Purchase::where('id', $this->receiptHeader->purchase_id)
                ->update([
                    'status' => 'final'
                ]);

            DB::commit();

            $this->showAlert('Proses Rollback Selesai');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->showAlert($e->getMessage(), 'danger', 'Error');
        }
    }

    public function render()
    {
        $rows = PurchaseDetail::with(['product', 'purchase', 'receiptDetails'])->where('purchase_id', $this->receiptHeader->purchase_id)
            ->paginate();

        $columns = [
            'id' => 'ID',
            'purchase.nomor_pembelian' => 'Nomor Pembelian',
            'product.kode_produk' => 'Kode Produk',
            'product.nama_produk' => 'Nama Produk',
            'qty' => 'Qty',
            'receiptDetails.qty_diterima' => 'Qty Terima'
        ];

        $canDelete = fn($row) => false;
        $canEdit = fn($row) => !in_array($row->purchase->status, ['received']);

        return view('livewire.menu.purchase-receipt-detail', compact(
            'rows',
            'columns',
            'canDelete',
            'canEdit'
        ));
    }
}
