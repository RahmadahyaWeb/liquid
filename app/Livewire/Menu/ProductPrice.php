<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\Product;
use App\Models\ProductPrice as ModelsProductPrice;

class ProductPrice extends BaseComponent
{
    public $modalTitle = 'Form Harga Produk';

    protected array $permissionMap = [
        'save' => ['edit price'],
        'edit' => ['edit price'],
        'delete' => ['delete price']
    ];

    public $editing =  [
        'id' => '',
        'product_id' => '',
        'customer_type' => '',
        'harga_jual' => '',
        'max_diskon' => '',
    ];

    public $productsGroup;

    public function mount()
    {
        $this->fetchProduct();
    }

    public function fetchProduct()
    {
        $this->productsGroup = Product::where('status', 1)->get();
    }

    public function rules()
    {
        return [
            'editing.product_id' => 'required',
            'editing.customer_type' => 'required',
            'editing.harga_jual' => 'required',
            'editing.max_diskon' => 'required',
        ];
    }

    public function create()
    {
        $this->validate();

        $this->executeSave(function () {
            ModelsProductPrice::updateOrCreate(
                [
                    'product_id' => $this->editing['product_id'],
                    'customer_type' => $this->editing['customer_type'],
                ],
                [
                    'harga_jual' => $this->editing['harga_jual'],
                    'max_diskon' => $this->editing['max_diskon'],
                ]
            );
        });
    }

    public function edit($id)
    {
        $this->editRecord($id, [
            'model' => ModelsProductPrice::class,
            'with' => ['product'],
            'map' => function ($data) {
                return [
                    'id' => $data->id,
                    'product_id' => $data->product_id,
                    'harga_jual' => $data->harga_jual,
                    'max_diskon' => $data->max_diskon,
                    'customer_type' => $data->customer_type,
                ];
            }
        ]);
    }

    public function delete($id)
    {
        $this->executeDelete(function () use ($id) {
            $productPrice = ModelsProductPrice::findOrFail($id);
            $productPrice->delete();
        });
    }

    public function save()
    {
        $this->validate();

        $this->executeSave(function () {
            ModelsProductPrice::updateOrCreate(
                [
                    'product_id' => $this->editing['product_id'],
                    'customer_type' => $this->editing['customer_type'],
                ],
                [
                    'harga_jual' => $this->editing['harga_jual'],
                    'max_diskon' => $this->editing['max_diskon'],
                ]
            );
        });
    }

    public function render()
    {
        $rows = ModelsProductPrice::with(['product'])
            ->paginate();

        $columns = [
            'product.kode_produk' => 'Kode Produk',
            'product.nama_produk' => 'Nama Produk',
            'customer_type' => 'Tipe Kustomer',
            'harga_jual' => 'Harga Jual',
            'max_diskon' => 'Max Diskon'
        ];

        $columnFormats = [
            'harga_jual' => fn($row) => $this->format_rupiah($row->harga_jual),
        ];

        return view('livewire.menu.product-price', compact(
            'rows',
            'columns',
            'columnFormats'
        ));
    }
}
