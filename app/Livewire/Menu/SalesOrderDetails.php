<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\SalesInvoice;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetails as ModelsSalesOrderDetails;
use Exception;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

class SalesOrderDetails extends BaseComponent
{
    public $modalTitle = 'Form Detail Sales Order';

    protected array $permissionMap = [
        'save' => ['edit sales-order-detail'],
        'edit' => ['edit sales-order-detail'],
        'delete' => ['delete sales-order-detail']
    ];

    public $editing =  [
        'id' => '',
        'sales_order_id' => '',
        'product_id' => '',
        'qty' => '',
        'diskonPercent' => 0,
        'harga_jual' => '',
        'max_diskon' => ''
    ];

    public $id;
    public $soHeader;
    public $productsGroup;

    public function mount($id)
    {
        $this->id = $id;

        $this->soHeader = SalesOrder::findOrFail($id);

        $this->fetchProducts();
    }

    public function fetchProducts()
    {
        $this->productsGroup = Product::where('status', 1)->get();
    }

    public function fetchProductPrices($productId = null)
    {
        return ProductPrice::where('product_id', $productId ?? $this->editing['product_id'])
            ->where('customer_type', $this->soHeader->customer->customer_type)
            ->first();
    }

    public function rules()
    {
        return [
            'editing.product_id' => 'required',
            'editing.qty' => 'required',
            'editing.diskonPercent' => 'required'
        ];
    }

    public function create()
    {
        $this->validate();

        $this->executeSave(function () {

            $this->validateMaxDiskon();

            $productPrice = $this->fetchProductPrices();

            $diskonPercent = $this->editing['diskonPercent'];
            $diskon = $productPrice->harga_jual * ($diskonPercent / 100);
            $hargaJual = $productPrice->harga_jual - $diskon;

            $subtotal = $hargaJual * $this->editing['qty'];

            ModelsSalesOrderDetails::create([
                'sales_order_id' => $this->id,
                'product_id' => $this->editing['product_id'],
                'qty' => $this->editing['qty'],
                'harga_jual' => $hargaJual,
                'diskon' => $diskon,
                'subtotal' => $subtotal
            ]);

            $this->updateTotalSalesOrder();
        });
    }

    public function updatedEditingProductId()
    {
        $productPrice = $this->fetchProductPrices();

        $this->editing['harga_jual'] = $productPrice->harga_jual;
        $this->editing['max_diskon'] = $productPrice->max_diskon;
    }

    public function validateMaxDiskon()
    {
        if (intval($this->editing['diskonPercent']) > intval($this->editing['max_diskon'])) {
            $this->addError('editing.diskonPercent', 'Melebihi batas maksimal diskon.');

            throw new Exception('Melebihi batas maksimal diskon.');
        }
    }

    public function updateTotalSalesOrder()
    {
        $totalBayar = ModelsSalesOrderDetails::where('sales_order_id', $this->id)
            ->sum('subtotal');
        $totalDiskon = ModelsSalesOrderDetails::where('sales_order_id', $this->id)
            ->sum('diskon');
        $totalHarga = ModelsSalesOrderDetails::where('sales_order_id', $this->id)
            ->sum('harga_jual');

        SalesOrder::where('id', $this->id)
            ->update([
                'total_bayar' => $totalBayar,
                'total_diskon' => $totalDiskon,
                'total_harga' => $totalHarga
            ]);
    }

    public function edit($id)
    {
        $this->editRecord($id, [
            'model' => ModelsSalesOrderDetails::class,
            'with' => ['product', 'salesOrder'],
            'map' => function ($data) {
                $productPrice = $this->fetchProductPrices($data->product_id);

                return [
                    'id' => $data->id,
                    'product_id' => $data->product_id,
                    'qty' => $data->qty,
                    'harga_jual' => $productPrice->harga_jual,
                    'max_diskon' => $productPrice->max_diskon,
                    'diskonPercent' => ($data->diskon / $productPrice->harga_jual) * 100
                ];
            }
        ]);
    }

    public function delete($id)
    {
        $this->executeDelete(function () use ($id) {
            $soDetail = ModelsSalesOrderDetails::findOrFail($id);
            $soDetail->delete();

            $this->updateTotalSalesOrder();
        });
    }

    public function save()
    {
        $this->validate();


        $this->executeSave(function () {

            $this->validateMaxDiskon();

            $soDetail = ModelsSalesOrderDetails::findOrFail($this->editing['id']);

            $productPrice = $this->fetchProductPrices();

            $diskonPercent = $this->editing['diskonPercent'];
            $diskon = $productPrice->harga_jual * ($diskonPercent / 100);
            $hargaJual = $productPrice->harga_jual - $diskon;

            $subtotal = $hargaJual * $this->editing['qty'];

            $soDetail->update([
                'sales_order_id' => $this->id,
                'product_id' => $this->editing['product_id'],
                'qty' => $this->editing['qty'],
                'harga_jual' => $hargaJual,
                'diskon' => $diskon,
                'subtotal' => $subtotal
            ]);

            $this->updateTotalSalesOrder();
        });
    }

    public function createInvoice()
    {
        DB::beginTransaction();

        try {
            SalesInvoice::generateInvoice($this->soHeader);

            DB::commit();

            $this->showAlert('Invoice berhasil dibuat.');
        } catch (Exception $e) {
            DB::rollBack();
            $this->showAlert($e->getMessage(), 'danger', 'Error');
        }
    }

    public function render()
    {
        $rows = ModelsSalesOrderDetails::with(['product', 'salesOrder'])
            ->where('sales_order_id', $this->id)
            ->paginate();

        $columns = [
            'salesOrder.no_so' => 'No SO',
            'product.kode_produk' => 'Kode Produk',
            'product.nama_produk' => 'Nama Produk',
            'qty' => 'Qty',
            'harga_jual' => 'Harga Jual',
            'diskon' => 'Diskon',
            'subtotal' => 'Subtotal',
        ];

        $columnFormats = [
            'harga_jual' => fn($row) => $this->format_rupiah($row->harga_jual),
            'diskon' => fn($row) => $this->format_rupiah($row->diskon),
            'subtotal' => fn($row) => $this->format_rupiah($row->subtotal),
        ];

        $cellClass = function ($row, $field) {
            $columnsWithClass = [
                'salesOrder.no_so',
                'harga_jual',
                'diskon',
                'subtotal',
                'product.kode_produk'
            ];

            if (in_array($field, $columnsWithClass)) {
                return 'whitespace-nowrap';
            }
            return '';
        };

        $canEdit = fn($row) => !in_array($row->salesOrder->status, ['invoiced']);
        $canDelete = fn($row) => !in_array($row->salesOrder->status, ['invoiced']);

        return view('livewire.menu.sales-order-details', compact(
            'rows',
            'columns',
            'columnFormats',
            'cellClass',
            'canEdit',
            'canDelete'
        ));
    }
}
