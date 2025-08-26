<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\Customer;
use App\Models\SalesOrder as ModelsSalesOrder;
use App\Models\Warehouse;

class SalesOrder extends BaseComponent
{
    public $modalTitle = 'Form Sales Order';

    protected array $permissionMap = [
        'save' => ['edit sales-order'],
        'edit' => ['edit sales-order'],
        'delete' => ['delete sales-order']
    ];

    public $editing =  [
        'id' => '',
        'no_so' => '',
        'customer_id' => '',
        'warehouse_id' => '',
        'tanggal' => '',
        'status' => '',
        'catatan' => '',
    ];

    public $customersGroup = [];
    public $warehousesGroup = [];

    public function mount()
    {
        $this->fetchCustomers();
        $this->fetchWarehouses();
    }

    public function fetchCustomers()
    {
        $this->customersGroup = Customer::all();
    }

    public function fetchWarehouses()
    {
        $this->warehousesGroup = Warehouse::where('status', 1)->get();
    }

    public function rules()
    {
        return [
            'editing.customer_id' => 'required',
            'editing.warehouse_id' => 'required',
            'editing.tanggal' => 'required',
        ];
    }

    public function create()
    {
        $this->validate();

        $this->executeSave(function () {
            ModelsSalesOrder::create([
                'no_so' => ModelsSalesOrder::generateNomorSalesOrder(),
                'customer_id' => $this->editing['customer_id'],
                'warehouse_id' => $this->editing['warehouse_id'],
                'tanggal' => $this->editing['tanggal'],
                'catatan' => $this->editing['catatan'],
            ]);
        });
    }

    public function edit($id)
    {
        $this->editRecord($id, [
            'model' => ModelsSalesOrder::class,
            'with' => ['customer', 'warehouse'],
            'map' => function ($data) {
                return [
                    'id' => $data->id,
                    'customer_id' => $data->customer_id,
                    'warehouse_id' => $data->warehouse_id,
                    'tanggal' => $data->tanggal,
                    'status' => $data->status,
                    'catatan' => $data->catatan,
                ];
            }
        ]);
    }

    public function save()
    {
        $this->validate();

        $this->executeSave(function () {
            $salesOrder = ModelsSalesOrder::findOrFail($this->editing['id']);

            $salesOrder->update([
                'customer_id' => $this->editing['customer_id'],
                'warehouse_id' => $this->editing['warehouse_id'],
                'tanggal' => $this->editing['tanggal'],
                'catatan' => $this->editing['catatan'],
                'status' => $this->editing['status']
            ]);
        });
    }

    public function delete($id)
    {
        $this->executeDelete(function () use ($id) {
            $salesOrder = ModelsSalesOrder::findOrFail($id);
            $salesOrder->delete();
        });
    }

    public function render()
    {
        $rows = ModelsSalesOrder::with(['customer', 'warehouse'])
            ->orderBy('no_so', 'desc')
            ->paginate();

        $columns = [
            'no_so' => 'No SO',
            'customer.kode_pelanggan' => 'Kode Pelanggan',
            'customer.nama_pelanggan' => 'Nama Pelanggan',
            'warehouse.nama_gudang' => 'Gudang',
            'tanggal' => 'Tanggal',
            'status' => 'Status',
            'total_diskon' => 'Total Diskon',
            'total_bayar' => 'Total Bayar',
            'catatan' => 'Catatan',
        ];

        $columnFormats = [
            'total_diskon' => fn($row) => $this->format_rupiah($row->total_diskon),
            'total_bayar' => fn($row) => $this->format_rupiah($row->total_bayar),
        ];

        $cellClass = function ($row, $field) {
            $columnsWithClass = ['no_so', 'customer.kode_pelanggan', 'tanggal', 'warehouse.nama_gudang', 'total_diskon', 'total_bayar'];

            if (in_array($field, $columnsWithClass)) {
                return 'whitespace-nowrap';
            }
            return '';
        };

        $canEdit = fn($row) => !in_array($row->status, ['invoiced']);
        $canDelete = fn($row) => !in_array($row->status, ['invoiced']);

        return view('livewire.menu.sales-order', compact(
            'rows',
            'columns',
            'columnFormats',
            'cellClass',
            'canEdit',
            'canDelete'
        ));
    }
}
