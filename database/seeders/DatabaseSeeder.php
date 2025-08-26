<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Config;
use App\Models\Province;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'thelastpc24@gmail.com'],
            [
                'name' => 'Rahmadahya',
                'password' => Hash::make('password')
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'user@user.com'],
            [
                'name' => 'User Dummy',
                'password' => Hash::make('password')
            ]
        );

        $permissions = [
            'view dashboard',

            // USER
            'view user',
            'edit user',
            'delete user',

            // ROLE 
            'view role',
            'edit role',
            'delete role',

            // SUPPLIER
            'view supplier',
            'edit supplier',
            'delete supplier',

            // PRODUCT CATEGORY
            'view category',
            'edit category',
            'delete category',

            // PRODUCT
            'view product',
            'edit product',
            'delete product',

            // WAREHOUSE
            'view warehouse',
            'edit warehouse',
            'delete warehouse',

            // STOCK
            'view stock',

            // PURCHASES
            'view purchase',
            'edit purchase',
            'delete purchase',

            // PURCHASE DETAILS
            'view purchase-detail',
            'edit purchase-detail',
            'delete purchase-detail',

            // FIFO 
            'view fifo-layers',
            'edit fifo-layers',
            'delete fifo-layers',

            // FIFO 
            'view fifo-usages',
            'edit fifo-usages',
            'delete fifo-usages',

            // RECEIPT
            'view receipt',
            'edit receipt',
            'delete receipt',

            // RECEIPT DETAILS
            'view receipt-detail',
            'edit receipt-detail',
            'delete receipt-detail',

            // PRODUCT PRICES
            'view price',
            'edit price',
            'delete price',

            // CUSTOMERS
            'view customer',
            'edit customer',
            'delete customer',

            // SALES ORDER
            'view sales-order',
            'edit sales-order',
            'delete sales-order',

            // SALES ORDER DETAILS
            'view sales-order-detail',
            'edit sales-order-detail',
            'delete sales-order-detail',

            // INVOICE
            'view sales-invoice',
            'edit sales-invoice',
            'delete sales-invoice',

            // INVOICE DETAILS
            'view sales-invoice-detail',
            'edit sales-invoice-detail',
            'delete sales-invoice-detail',

            // REPORT
            'view report-produk',
            'view report-stok',
            'view report-penjualan',
            'view report-pembelian',
            'view report-fifo',

            // CONFIG
            'view config',
            'edit config',


            // AR
            'view ar',
            'view pembayaran',
            'edit pembayaran',
            'delete pembayaran',
            'view report-ar',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $role = Role::firstOrCreate(['name' => 'admin']);

        $staffRole = Role::firstOrCreate(['name' => 'staff']);

        $role->syncPermissions($permissions);
        $staffRole->syncPermissions($permissions);

        if (!$admin->hasRole($role->name)) {
            $admin->assignRole($role);
        }

        if (!$user->hasRole($role->name)) {
            $user->assignRole($staffRole);
        }

        Config::firstOrCreate(
            ['config' => 'nama_toko'],
            [
                'value' => 'Toko Ana',
            ]
        );

        Config::firstOrCreate(
            ['config' => 'alamat_toko'],
            [
                'value' => 'Batulicin',
            ]
        );

        Config::firstOrCreate(
            ['config' => 'telepon_toko'],
            [
                'value' => '081247189174',
            ]
        );

        Config::firstOrCreate(
            ['config' => 'email_toko'],
            [
                'value' => 'toko@gmail.com',
            ]
        );
        $kalTengah = Province::firstOrCreate(
            ['kode_provinsi' => '6200'],
            ['nama_provinsi' => 'Kalimantan Tengah']
        );

        $kalSelatan = Province::firstOrCreate(
            ['kode_provinsi' => '6300'],
            ['nama_provinsi' => 'Kalimantan Selatan']
        );

        // mapping untuk memudahkan
        $provinceMap = [
            '6200' => $kalTengah->id,
            '6300' => $kalSelatan->id,
        ];

        $cities = [
            ['province_kode' => '6200', 'kode_kabupaten' => '6201', 'nama_kabupaten' => 'Kabupaten Kotawaringin Barat'],
            ['province_kode' => '6200', 'kode_kabupaten' => '6202', 'nama_kabupaten' => 'Kabupaten Kotawaringin Timur'],
            ['province_kode' => '6200', 'kode_kabupaten' => '6203', 'nama_kabupaten' => 'Kabupaten Kapuas'],
            ['province_kode' => '6200', 'kode_kabupaten' => '6204', 'nama_kabupaten' => 'Kabupaten Barito Selatan'],
            ['province_kode' => '6200', 'kode_kabupaten' => '6205', 'nama_kabupaten' => 'Kabupaten Barito Utara'],
            ['province_kode' => '6200', 'kode_kabupaten' => '6206', 'nama_kabupaten' => 'Kabupaten Sukamara'],
            ['province_kode' => '6200', 'kode_kabupaten' => '6207', 'nama_kabupaten' => 'Kabupaten Lamandau'],
            ['province_kode' => '6200', 'kode_kabupaten' => '6208', 'nama_kabupaten' => 'Kabupaten Seruyan'],
            ['province_kode' => '6200', 'kode_kabupaten' => '6209', 'nama_kabupaten' => 'Kabupaten Katingan'],
            ['province_kode' => '6200', 'kode_kabupaten' => '6210', 'nama_kabupaten' => 'Kabupaten Pulang Pisau'],
            ['province_kode' => '6200', 'kode_kabupaten' => '6211', 'nama_kabupaten' => 'Kabupaten Gunung Mas'],
            ['province_kode' => '6200', 'kode_kabupaten' => '6212', 'nama_kabupaten' => 'Kabupaten Barito Timur'],
            ['province_kode' => '6200', 'kode_kabupaten' => '6213', 'nama_kabupaten' => 'Kabupaten Murung Raya'],
            ['province_kode' => '6200', 'kode_kabupaten' => '6271', 'nama_kabupaten' => 'Kota Palangka Raya'],
            ['province_kode' => '6300', 'kode_kabupaten' => '6301', 'nama_kabupaten' => 'Kabupaten Tanah Laut'],
            ['province_kode' => '6300', 'kode_kabupaten' => '6302', 'nama_kabupaten' => 'Kabupaten Kotabaru'],
            ['province_kode' => '6300', 'kode_kabupaten' => '6303', 'nama_kabupaten' => 'Kabupaten Banjar'],
            ['province_kode' => '6300', 'kode_kabupaten' => '6304', 'nama_kabupaten' => 'Kabupaten Barito Kuala'],
            ['province_kode' => '6300', 'kode_kabupaten' => '6305', 'nama_kabupaten' => 'Kabupaten Tapin'],
            ['province_kode' => '6300', 'kode_kabupaten' => '6306', 'nama_kabupaten' => 'Kabupaten Hulu Sungai Selatan'],
            ['province_kode' => '6300', 'kode_kabupaten' => '6307', 'nama_kabupaten' => 'Kabupaten Hulu Sungai Tengah'],
            ['province_kode' => '6300', 'kode_kabupaten' => '6308', 'nama_kabupaten' => 'Kabupaten Hulu Sungai Utara'],
            ['province_kode' => '6300', 'kode_kabupaten' => '6309', 'nama_kabupaten' => 'Kabupaten Tabalong'],
            ['province_kode' => '6300', 'kode_kabupaten' => '6310', 'nama_kabupaten' => 'Kabupaten Tanah Bumbu'],
            ['province_kode' => '6300', 'kode_kabupaten' => '6311', 'nama_kabupaten' => 'Kabupaten Balangan'],
            ['province_kode' => '6300', 'kode_kabupaten' => '6371', 'nama_kabupaten' => 'Kota Banjarmasin'],
            ['province_kode' => '6300', 'kode_kabupaten' => '6372', 'nama_kabupaten' => 'Kota Banjar Baru'],
        ];

        foreach ($cities as $city) {
            City::firstOrCreate(
                ['kode_kabupaten' => $city['kode_kabupaten']],
                [
                    'province_id'    => $provinceMap[$city['province_kode']], // ambil id sesuai FK
                    'nama_kabupaten' => $city['nama_kabupaten'],
                ]
            );
        }
    }
}
