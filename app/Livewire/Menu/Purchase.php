<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\Purchase as ModelsPurchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;

class Purchase extends BaseComponent
{
    public $modalTitle = 'Form Pembelian';

    protected array $permissionMap = [
        'save' => ['edit purchase'],
        'edit' => ['edit purchase'],
        'delete' => ['delete purchase']
    ];

    public $editing =  [
        'id' => '',
        'nomor_pembelian' => '',
        'supplier_id' => '',
        'warehouse_id' => '',
        'tanggal_pembelian' => '',
        'total_pembelian' => '',
        'status' => '',
    ];

    public $suppliersGroup = [];
    public $warehousesGroup = [];

    public function mount()
    {
        $this->fetchSuppliers();
        $this->fetchWarehouses();
    }

    public function fetchSuppliers()
    {
        $this->suppliersGroup = Supplier::where('status', 1)->get();
    }

    public function fetchWarehouses()
    {
        $this->warehousesGroup = Warehouse::where('status', 1)->get();
    }

    public function rules()
    {
        return [
            'editing.supplier_id' => 'required',
            'editing.warehouse_id' => 'required',
            'editing.tanggal_pembelian' => 'required',
        ];
    }

    public function create()
    {
        $this->validate();

        $this->executeSave(function () {
            ModelsPurchase::create([
                'nomor_pembelian' => ModelsPurchase::generateNomorPembelian(),
                'supplier_id' => $this->editing['supplier_id'],
                'warehouse_id' => $this->editing['warehouse_id'],
                'tanggal_pembelian' => $this->editing['tanggal_pembelian'],
                'status' => 'draft',
                'created_by' => Auth::id()
            ]);
        });
    }

    public function edit($id)
    {
        $this->editRecord($id, [
            'model' => ModelsPurchase::class,
            'with' => ['creator', 'updater', 'warehouse', 'supplier'],
            'map' => function ($data) {
                return [
                    'id' => $data->id,
                    'supplier_id' => $data->supplier_id,
                    'warehouse_id' => $data->warehouse_id,
                    'tanggal_pembelian' => $data->tanggal_pembelian,
                    'status' => $data->status,
                ];
            }
        ]);
    }

    public function save()
    {
        $this->validate();

        $this->executeSave(function () {
            $purchase = ModelsPurchase::findOrFail($this->editing['id']);

            $purchase->update([
                'supplier_id' => $this->editing['supplier_id'],
                'warehouse_id' => $this->editing['warehouse_id'],
                'tanggal_pembelian' => $this->editing['tanggal_pembelian'],
                'status' => $this->editing['status'],
                'updated_by' => Auth::id()
            ]);
        });
    }

    public function delete($id)
    {
        $this->executeDelete(function () use ($id) {
            $purchase = ModelsPurchase::findOrFail($id);
            $purchase->delete();
        });
    }

    public function render()
    {
        $rows = ModelsPurchase::with(['creator', 'updater', 'warehouse', 'supplier'])
            ->orderBy('nomor_pembelian', 'desc')
            ->paginate();

        $columns = [
            'nomor_pembelian' => 'Nomor Pembelian',
            'supplier.nama_supplier' => 'Nama Supplier',
            'warehouse.nama_gudang' => 'Nama Gudang',
            'tanggal_pembelian' => 'Tanggal Pembelian',
            'total_pembelian' => 'Total Pembelian',
            'status' => 'Status',
            'creator.name' => 'Dibuat Oleh',
            'updater.name' => 'Diupdate Oleh'
        ];

        $columnFormats = [
            'total_pembelian' => fn($row) => $this->format_rupiah($row->total_pembelian),
        ];

        $cellClass = function ($row, $field) {
            $columnsWithClass = ['nomor_pembelian', 'tanggal_pembelian', 'supplier.nama_supplier', 'warehouse.nama_gudang', 'total_pembelian'];

            if (in_array($field, $columnsWithClass)) {
                return 'whitespace-nowrap';
            }
            return '';
        };

        $canEdit = fn($row) => !in_array($row->status, ['final', 'receiving', 'received']);
        $canDelete = fn($row) => !in_array($row->status, ['final', 'receiving', 'received']);

        return view('livewire.menu.purchase', compact(
            'rows',
            'columns',
            'columnFormats',
            'canEdit',
            'canDelete',
            'cellClass'
        ));
    }
}
