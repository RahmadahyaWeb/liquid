<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\Product as ModelsProduct;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Models\WarehouseStock;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

class Product extends BaseComponent
{
    use WithFileUploads;

    public $modalTitle = 'Form Produk';

    protected array $permissionMap = [
        'save' => ['edit product'],
        'edit' => ['edit product'],
        'delete' => ['delete product']
    ];

    public $editing =  [
        'id' => '',
        'kode_produk' => '',
        'nama_produk' => '',
        'deskripsi' => '',
        'foto_produk' => '',
        'product_category_id' => '',
        'supplier_id' => '',
        'status' => '',
        'harga_beli' => 0,
    ];

    public $categoryGroup = [];
    public $supplierGroup = [];

    public function mount()
    {
        $this->fetchCategory();
        $this->fetchSupplier();
    }

    public function fetchCategory()
    {
        $this->categoryGroup = ProductCategory::all();
    }

    public function fetchSupplier()
    {
        $this->supplierGroup = Supplier::where('status', 1)->get();
    }

    public function create()
    {
        $this->validate([
            'editing.kode_produk' => 'required|unique:products,kode_produk',
            'editing.nama_produk' => 'required',
            'editing.product_category_id' => 'required',
            'editing.status' => 'required',
            'editing.foto_produk' => 'image|max:1024',
            'editing.harga_beli' => 'required',
            'editing.supplier_id' => 'required',
        ]);

        $this->executeSave(function () {
            $fotoPath = null;

            if ($this->editing['foto_produk']) {
                $fotoPath = $this->editing['foto_produk']->storeAs(
                    'photos',
                    $this->editing['foto_produk']->hashName(),
                    'public'
                );
            }

            $kode_produk = strtoupper($this->editing['kode_produk']);

            ModelsProduct::create([
                'kode_produk' => $kode_produk,
                'nama_produk' => $this->editing['nama_produk'],
                'product_category_id' => $this->editing['product_category_id'],
                'deskripsi' => $this->editing['deskripsi'],
                'status' => $this->editing['status'],
                'foto_produk' => $fotoPath,
                'harga_beli' => $this->editing['harga_beli'],
                'supplier_id' => $this->editing['supplier_id'],
            ]);
        });
    }

    public function edit($id)
    {
        $this->editRecord($id, [
            'model' => ModelsProduct::class,
            'with' => ['category'],
            'map' => function ($data) {
                return [
                    'id' => $data->id,
                    'kode_produk' => $data->kode_produk,
                    'nama_produk' => $data->nama_produk,
                    'product_category_id' => $data->product_category_id,
                    'deskripsi' => $data->deskripsi,
                    'status' => $data->status,
                    'harga_beli' => $data->harga_beli,
                    'supplier_id' => $data->supplier_id,
                ];
            }
        ]);
    }

    public function save()
    {
        $produk = ModelsProduct::findOrFail($this->editing['id']);

        $this->validate([
            'editing.kode_produk' => 'required|unique:products,kode_produk, ' . $produk->id,
            'editing.nama_produk' => 'required',
            'editing.product_category_id' => 'required',
            'editing.status' => 'required',
            'editing.supplier_id' => 'required',
            'editing.foto_produk' => 'image|max:1024'
        ]);

        $this->executeSave(function () use ($produk) {

            $fotoPath = $produk->foto_produk;

            if (isset($this->editing['foto_produk'])) {
                $fotoPath = $this->editing['foto_produk']->storeAs(
                    'photos',
                    $this->editing['foto_produk']->hashName(),
                    'public'
                );
            }

            $kode_produk = strtoupper($this->editing['kode_produk']);

            $produk->update([
                'kode_produk' => $kode_produk,
                'nama_produk' => $this->editing['nama_produk'],
                'product_category_id' => $this->editing['product_category_id'],
                'deskripsi' => $this->editing['deskripsi'],
                'status' => $this->editing['status'],
                'supplier_id' => $this->editing['supplier_id'],
                'foto_produk' => $fotoPath,
            ]);
        });
    }

    public function delete($id)
    {
        $this->executeDelete(function () use ($id) {
            $produk = ModelsProduct::findOrFail($id);
            $produk->delete();
        });
    }

    public function render()
    {
        $rows = ModelsProduct::with('category')
            ->select([
                'id',
                'nama_produk',
                'kode_produk',
                'deskripsi',
                'product_category_id',
                'foto_produk',
                'harga_beli',
                'supplier_id',
                DB::raw("CASE WHEN status = 1 THEN 'Aktif' ELSE 'Nonaktif' END as status")
            ])
            ->paginate();

        $columns = [
            'nama_produk' => 'Nama Produk',
            'kode_produk' => 'Kode Produk',
            'supplier.kode_supplier' => 'Kode Supplier',
            'deskripsi' => 'Deskripsi Produk',
            'category.nama_kategori' => 'Kategori Produk',
            'foto_produk' => 'Foto Produk',
            'harga_beli' => 'Harga Beli',
            'status' => 'Status',
        ];

        $columnFormats = [
            'foto_produk' => 'image',
            'harga_beli' => fn($row) => $this->format_rupiah($row->harga_beli),
        ];

        return view('livewire.menu.product', compact(
            'rows',
            'columns',
            'columnFormats',
        ));
    }
}
