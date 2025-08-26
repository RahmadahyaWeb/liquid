<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\City;
use App\Models\Customer as ModelsCustomer;

class Customer extends BaseComponent
{
    public $modalTitle = 'Form Pelanggan';

    protected array $permissionMap = [
        'save' => ['edit customer'],
        'edit' => ['edit customer'],
        'delete' => ['delete customer']
    ];

    public $editing =  [
        'id' => '',
        'kode_pelanggan' => '',
        'nama_pelanggan' => '',
        'kontak' => '',
        'alamat' => '',
        'customer_type' => '',
        'city_id' => '',
        'TOP' => 0,
    ];

    public $cityGroup = [];

    public function mount()
    {
        $this->fetchCities();
    }

    public function fetchCities()
    {
        $this->cityGroup = City::all();
    }

    public function rules()
    {
        return [
            'editing.nama_pelanggan' => 'required',
            'editing.kontak' => 'required',
            'editing.alamat' => 'required',
            'editing.customer_type' => 'required',
            'editing.city_id' => 'required',
            'editing.TOP' => 'required',
        ];
    }

    public function create()
    {
        $this->validate();

        $this->executeSave(function () {
            ModelsCustomer::create([
                'nama_pelanggan' => $this->editing['nama_pelanggan'],
                'kode_pelanggan' => ModelsCustomer::generateKodePelanggan(),
                'kontak' => $this->editing['kontak'],
                'alamat' => $this->editing['alamat'],
                'customer_type' => $this->editing['customer_type'],
                'city_id' => $this->editing['city_id'],
                'TOP' => $this->editing['TOP'],
            ]);
        });
    }

    public function edit($id)
    {
        $this->editRecord($id, [
            'model' => ModelsCustomer::class,
            'with' => [],
            'map' => function ($data) {
                return [
                    'id' => $data->id,
                    'nama_pelanggan' => $data->nama_pelanggan,
                    'kontak' => $data->kontak,
                    'alamat' => $data->alamat,
                    'customer_type' => $data->customer_type,
                    'city_id' => $data->city_id,
                    'TOP' => $data->TOP,
                ];
            }
        ]);
    }

    public function save()
    {
        $this->validate();

        $this->executeSave(function () {
            $customer = ModelsCustomer::findOrFail($this->editing['id']);

            $customer->update([
                'nama_pelanggan' => $this->editing['nama_pelanggan'],
                'kontak' => $this->editing['kontak'],
                'alamat' => $this->editing['alamat'],
                'customer_type' => $this->editing['customer_type'],
                'city_id' => $this->editing['city_id'],
                'TOP' => $this->editing['TOP'],
            ]);
        });
    }

    public function delete($id)
    {
        $this->executeDelete(function () use ($id) {
            $customer = ModelsCustomer::findOrFail($id);
            $customer->delete();
        });
    }

    public function render()
    {
        $rows = ModelsCustomer::paginate();

        $columns = [
            'kode_pelanggan' => 'Kode Pelanggan',
            'customer_type' => 'Tipe Customer',
            'nama_pelanggan' => 'Nama Pelanggan',
            'kontak' => 'Kontak',
            'alamat' => 'Alamat',
            'city.nama_kabupaten' => 'Kabupaten / Kota',
            'TOP' => 'TOP',
        ];

        return view('livewire.menu.customer', compact(
            'rows',
            'columns'
        ));
    }
}
