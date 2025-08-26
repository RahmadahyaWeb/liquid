<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\Supplier as ModelsSupplier;
use Illuminate\Support\Facades\DB;

class Supplier extends BaseComponent
{
    public $modalTitle = 'Form Supplier';

    protected array $permissionMap = [
        'save' => ['edit supplier'],
        'edit' => ['edit supplier'],
        'delete' => ['delete supplier']
    ];

    public $editing =  [
        'id' => '',
        'kode_supplier' => '',
        'nama_supplier' => '',
        'kontak' => '',
        'alamat' => '',
        'status' => '',
    ];

    public function rules()
    {
        return [
            'editing.nama_supplier' => 'required',
            'editing.status' => 'required'
        ];
    }

    public function create()
    {
        $this->validate();

        $this->executeSave(function () {
            ModelsSupplier::create([
                'kode_supplier' => $this->editing['kode_supplier'],
                'nama_supplier' => $this->editing['nama_supplier'],
                'kontak' => $this->editing['kontak'],
                'alamat' => $this->editing['alamat'],
                'status' => $this->editing['status'],
            ]);
        });
    }

    public function edit($id)
    {
        $this->editRecord($id, [
            'model' => ModelsSupplier::class,
            'with' => [],
            'map' => function ($data) {
                return [
                    'id' => $data->id,
                    'kode_supplier' => $data->kode_supplier,
                    'nama_supplier' => $data->nama_supplier,
                    'kontak' => $data->kontak,
                    'alamat' => $data->alamat,
                    'status' => $data->status,
                ];
            }
        ]);
    }

    public function save()
    {
        $this->validate();

        $this->executeSave(function () {
            $supplier = ModelsSupplier::findOrFail($this->editing['id']);

            $supplier->update([
                'kode_supplier' => $this->editing['kode_supplier'],
                'nama_supplier' => $this->editing['nama_supplier'],
                'kontak' => $this->editing['kontak'],
                'alamat' => $this->editing['alamat'],
                'status' => $this->editing['status'],
            ]);
        });
    }

    public function delete($id)
    {
        $this->executeDelete(function () use ($id) {
            $supplier = ModelsSupplier::findOrFail($id);
            $supplier->delete();
        });
    }

    public function render()
    {
        $rows = ModelsSupplier::select([
            'id',
            'kode_supplier',
            'nama_supplier',
            'kontak',
            'alamat',
            DB::raw("CASE WHEN status = 1 THEN 'Aktif' ELSE 'Nonaktif' END as status")
        ])->paginate();


        $columns = [
            'kode_supplier' => 'Kode Supplier',
            'nama_supplier' => 'Nama Supplier',
            'kontak' => 'Kontak',
            'alamat' => 'Alamat',
            'status' => 'Status',
        ];

        return view('livewire.menu.supplier', compact('rows', 'columns'));
    }
}
