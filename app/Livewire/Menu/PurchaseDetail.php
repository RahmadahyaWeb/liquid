<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetail as ModelsPurchaseDetail;

class PurchaseDetail extends BaseComponent
{
    public $id;

    public $modalTitle = 'Form Detail Pembelian';

    protected array $permissionMap = [
        'save' => ['edit purchase-detail'],
        'edit' => ['edit purchase-detail'],
        'delete' => ['delete purchase-detail']
    ];

    public $editing =  [
        'id' => '',
        'purchase_id' => '',
        'product_id' => '',
        'qty' => '',
        'harga_modal' => '',
        'subtotal' => '',
    ];

    public $purchaseHeader;
    public $productsGroup;

    public function mount($id)
    {
        $this->id = $id;

        $this->purchaseHeader = Purchase::findOrFail($this->id);

        $this->fetchProduct();
    }

    public function fetchProduct()
    {
        $this->productsGroup = Product::where('status', 1)
            ->where('supplier_id', $this->purchaseHeader->supplier_id)
            ->get();
    }

    public function rules()
    {
        return [
            'editing.product_id' => 'required',
            'editing.qty' => 'required',
            'editing.harga_modal' => 'required',
        ];
    }

    public function updateTotalPembelian()
    {
        $total = ModelsPurchaseDetail::where('purchase_id', $this->id)
            ->sum('subtotal');

        Purchase::where('id', $this->id)
            ->update([
                'total_pembelian' => $total
            ]);
    }

    public function updatedEditingProductId($id)
    {
        $this->editing['harga_modal'] = Product::where('id', $id)->value('harga_beli');
    }

    public function create()
    {
        $this->validate();

        $this->executeSave(function () {
            $subtotalDetail = $this->editing['harga_modal'] * $this->editing['qty'];

            ModelsPurchaseDetail::create([
                'purchase_id' => $this->id,
                'product_id' => $this->editing['product_id'],
                'qty' => $this->editing['qty'],
                'harga_modal' => $this->editing['harga_modal'],
                'subtotal' => $subtotalDetail
            ]);

            $this->updateTotalPembelian();
        });
    }

    public function edit($id)
    {
        $this->editRecord($id, [
            'model' => ModelsPurchaseDetail::class,
            'with' => ['product', 'purchase'],
            'map' => function ($data) {
                return [
                    'id' => $data->id,
                    'product_id' => $data->product_id,
                    'qty' => $data->qty,
                    'harga_modal' => $data->harga_modal,
                    'subtotal' => $data->subtotal,
                ];
            }
        ]);
    }

    public function save()
    {
        $this->validate();

        $this->executeSave(function () {
            $purchaseDetail = ModelsPurchaseDetail::findOrFail($this->editing['id']);

            $subtotalDetail = $this->editing['harga_modal'] * $this->editing['qty'];

            $purchaseDetail->update([
                'product_id' => $this->editing['product_id'],
                'qty' => $this->editing['qty'],
                'harga_modal' => $this->editing['harga_modal'],
                'subtotal' => $subtotalDetail
            ]);

            $this->updateTotalPembelian();
        });
    }

    public function delete($id)
    {
        $this->executeDelete(function () use ($id) {
            $purchaseDetail = ModelsPurchaseDetail::findOrFail($id);
            $purchaseDetail->delete();

            $this->updateTotalPembelian();
        });
    }

    public function render()
    {
        $rows = ModelsPurchaseDetail::with(['product', 'purchase'])
            ->where('purchase_id', $this->id)
            ->paginate();

        $columns = [
            'purchase.nomor_pembelian' => 'Nomor Pembelian',
            'product.nama_produk' => 'Nama Produk',
            'qty' => 'Qty',
            'harga_modal' => 'Harga',
            'subtotal' => 'Subtotal'
        ];

        $columnFormats = [
            'harga_modal' => fn($row) => $this->format_rupiah($row->harga_modal),
            'subtotal' => fn($row) => $this->format_rupiah($row->subtotal),
        ];

        $canEdit = fn($row) => !in_array(optional($row->purchase)->status, ['final', 'receiving', 'received']);
        $canDelete = fn($row) => !in_array(optional($row->purchase)->status, ['final', 'receiving', 'received']);

        return view('livewire.menu.purchase-detail', compact(
            'rows',
            'columns',
            'columnFormats',
            'canEdit',
            'canDelete'
        ));
    }
}
