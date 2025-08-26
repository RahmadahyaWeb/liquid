<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\ProductCategory as ModelsProductCategory;
use Livewire\Component;

class ProductCategory extends BaseComponent
{
    public $modalTitle = 'Form Kategori Produk';

    protected array $permissionMap = [
        'save' => ['edit category'],
        'edit' => ['edit category'],
        'delete' => ['delete category']
    ];

    public $editing =  [
        'id' => '',
        'nama_kategori' => '',
    ];

    public function rules()
    {
        return [
            'editing.nama_kategori' => 'required',
        ];
    }

    public function create()
    {
        $this->validate();

        $this->executeSave(function () {
            ModelsProductCategory::create([
                'nama_kategori' => $this->editing['nama_kategori']
            ]);
        });
    }

    public function edit($id)
    {
        $this->editRecord($id, [
            'model' => ModelsProductCategory::class,
            'with' => [],
            'map' => function ($data) {
                return [
                    'id' => $data->id,
                    'nama_kategori' => $data->nama_kategori,
                ];
            }
        ]);
    }

    public function save()
    {
        $this->validate();

        $this->executeSave(function () {
            $productCategory = ModelsProductCategory::findOrFail($this->editing['id']);

            $productCategory->update([
                'nama_kategori' => $this->editing['nama_kategori']
            ]);
        });
    }

    public function delete($id)
    {
        $this->executeDelete(function () use ($id) {
            $productCategory = ModelsProductCategory::findOrFail($id);
            $productCategory->delete();
        });
    }

    public function render()
    {
        $rows = ModelsProductCategory::paginate();

        $columns = [
            'nama_kategori' => 'Nama Kategori',
        ];

        return view('livewire.menu.product-category', compact(
            'rows',
            'columns'
        ));
    }
}
