<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\Warehouse as ModelsWarehouse;
use Illuminate\Support\Facades\DB;

class Warehouse extends BaseComponent
{
    public $modalTitle = 'Form Gudang';

    protected array $permissionMap = [
        'save' => ['edit warehouse'],
        'edit' => ['edit warehouse'],
        'delete' => ['delete warehouse']
    ];

    public $editing =  [
        'id' => '',
        'nama_gudang' => '',
        'status' => '',
    ];

    public function rules()
    {
        return [
            'editing.nama_gudang' => 'required',
            'editing.status' => 'required',
        ];
    }

    public function create()
    {
        $this->validate();

        $this->executeSave(function () {
            ModelsWarehouse::create([
                'nama_gudang' => $this->editing['nama_gudang'],
                'status' => $this->editing['status']
            ]);
        });
    }

    public function edit($id)
    {
        $this->editRecord($id, [
            'model' => ModelsWarehouse::class,
            'with' => [],
            'map' => function ($data) {
                return [
                    'id' => $data->id,
                    'nama_gudang' => $data->nama_gudang,
                    'status' => $data->status,
                ];
            }
        ]);
    }

    public function save()
    {
        $this->validate();

        $this->executeSave(function () {
            $warehouse = ModelsWarehouse::findOrFail($this->editing['id']);

            $warehouse->update([
                'nama_gudang' => $this->editing['nama_gudang'],
                'status' => $this->editing['status']
            ]);
        });
    }

    public function delete($id)
    {
        $this->executeDelete(function () use ($id) {
            $warehouse = ModelsWarehouse::findOrFail($id);
            $warehouse->delete();
        });
    }

    public function render()
    {
        $rows = ModelsWarehouse::select([
            'id',
            'nama_gudang',
            DB::raw("CASE WHEN status = 1 THEN 'Aktif' ELSE 'Nonaktif' END as status")
        ])->paginate();

        $columns = [
            'nama_gudang' => 'Nama Gudang',
            'status' => 'Status'
        ];

        return view('livewire.menu.warehouse', compact(
            'rows',
            'columns'
        ));
    }
}
