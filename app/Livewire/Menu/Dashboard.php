<?php

namespace App\Livewire\Menu;

use App\Livewire\BaseComponent;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends BaseComponent
{
    public $topProducts;
    public $topCustomers;
    public $topPurchases;
    public $salesOrderDraftCount;

    public function fetchTopProducts()
    {
        $topProducts = Product::select('products.id', 'products.nama_produk', 'products.kode_produk', DB::raw('SUM(sid.qty) as total_terjual'))
            ->join('sales_invoice_details as sid', 'products.id', '=', 'sid.product_id')
            ->groupBy('products.id', 'products.nama_produk', 'products.kode_produk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $this->topProducts = $topProducts;
    }

    public function fetchTopCustomers()
    {
        $topCustomers = Customer::select('customers.id', 'customers.nama_pelanggan', 'customers.kode_pelanggan', DB::raw('COUNT(sales_invoices.id) as total_invoice'))
            ->join('sales_invoices', 'customers.id', '=', 'sales_invoices.customer_id')
            ->groupBy('customers.id', 'customers.nama_pelanggan', 'customers.kode_pelanggan')
            ->orderByDesc('total_invoice')
            ->limit(5)
            ->get();

        $this->topCustomers = $topCustomers;
    }

    public function fetchTopPurchases()
    {
        $topPurchases = Product::select('products.id', 'products.nama_produk', 'products.kode_produk', DB::raw('SUM(pd.qty) as total_terbeli'))
            ->join('purchase_details as pd', 'products.id', '=', 'pd.product_id')
            ->groupBy('products.id', 'products.nama_produk', 'products.kode_produk')
            ->orderByDesc('total_terbeli')
            ->limit(5)
            ->get();

        $this->topPurchases = $topPurchases;
    }

    public function showDraftSalesOrder()
    {
        $salesOrderDraftCount = SalesOrder::where('status', 'draft')
            ->count();

        $this->salesOrderDraftCount = $salesOrderDraftCount;
    }

    public function mount()
    {
        $this->fetchTopProducts();
        $this->fetchTopCustomers();
        $this->fetchTopPurchases();
        $this->showDraftSalesOrder();
    }

    public function render()
    {
        return view('livewire.menu.dashboard');
    }
}
