<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\Purchase;
use App\Models\PurchaseReceipt as ModelsPurchaseReceipt;
use App\Models\Warehouse;

class PurchaseReceipt extends BaseComponent
{
    public $modalTitle = 'Form Penerimaan';

    protected array $permissionMap = [
        'save' => ['edit receipt'],
        'edit' => ['edit receipt'],
        'delete' => ['delete receipt']
    ];

    public $editing =  [
        'id' => '',
        'purchase_id' => '',
        'tanggal_penerimaan' => '',
        'kode_penerimaan' => '',
        'warehouse_id' => '',
    ];

    public $purchasesGroup = [];
    public $warehousesGroup = [];

    public function mount()
    {
        $this->fetchPurchases();
    }

    public function fetchPurchases()
    {
        $this->purchasesGroup = Purchase::where('status', 'final')->get();
    }

    public function rules()
    {
        return [
            'editing.purchase_id' => 'required',
            'editing.tanggal_penerimaan' => 'required',
        ];
    }

    public function create()
    {
        $this->validate();

        $this->executeSave(function () {
            $warehouseId = Purchase::where('id', $this->editing['purchase_id'])
                ->value('warehouse_id');

            ModelsPurchaseReceipt::create([
                'kode_penerimaan' => ModelsPurchaseReceipt::generateNomorPenerimaan(),
                'purchase_id' => $this->editing['purchase_id'],
                'warehouse_id' => $warehouseId,
                'tanggal_penerimaan' => $this->editing['tanggal_penerimaan']
            ]);

            Purchase::where('id', $this->editing['purchase_id'])
                ->update([
                    'status' => 'receiving'
                ]);
        });
    }

    public function edit($id)
    {
        $this->editRecord($id, [
            'model' => ModelsPurchaseReceipt::class,
            'with' => ['purchase', 'warehouse'],
            'map' => function ($data) {
                return [
                    'id' => $data->id,
                    'purchase_id' => $data->purchase_id,
                    'warehouse_id' => $data->warehouse_id,
                    'tanggal_penerimaan' => $data->tanggal_penerimaan,
                ];
            }
        ]);
    }

    public function save()
    {
        $this->validate();

        $this->executeSave(function () {
            $purchaseReceipt = ModelsPurchaseReceipt::findOrFail($this->editing['id']);

            $purchaseReceipt->update([
                'purchase_id' => $this->editing['purchase_id'],
                'warehouse_id' => $this->editing['warehouse_id'],
                'tanggal_penerimaan' => $this->editing['tanggal_penerimaan']
            ]);
        });
    }

    public function delete($id)
    {
        $this->executeDelete(function () use ($id) {
            $purchaseReceipt = ModelsPurchaseReceipt::findOrFail($id);

            Purchase::where('id', $purchaseReceipt->purchase_id)
                ->update([
                    'status' => 'final'
                ]);

            $purchaseReceipt->delete();
        });
    }

    public function render()
    {
        $rows = ModelsPurchaseReceipt::with(['purchase', 'warehouse'])
            ->orderBy('kode_penerimaan', 'desc')
            ->paginate();

        $columns = [
            'kode_penerimaan' => 'Kode Penerimaan',
            'purchase.nomor_pembelian' => 'Nomor Pembelian',
            'warehouse.nama_gudang' => 'Nama Gudang',
            'tanggal_penerimaan' => 'Tanggal Penerimaan',
            'purchase.status' => 'Status'
        ];

        $cellClass = function ($row, $field) {
            $columnsWithClass = [
                'kode_penerimaan',
                'purchase.nomor_pembelian',
            ];

            if (in_array($field, $columnsWithClass)) {
                return 'whitespace-nowrap';
            }
            return '';
        };

        $canEdit = fn($row) => !in_array($row->purchase->status, ['received']);
        $canDelete = fn($row) => !in_array($row->purchase->status, ['received']);

        return view('livewire.menu.purchase-receipt', compact(
            'rows',
            'columns',
            'canEdit',
            'canDelete',
            'cellClass'
        ));
    }
}
