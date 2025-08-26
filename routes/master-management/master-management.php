<?php

use App\Livewire\Menu\Customer;
use App\Livewire\Menu\Product;
use App\Livewire\Menu\ProductCategory;
use App\Livewire\Menu\ProductPrice;
use App\Livewire\Menu\Supplier;
use App\Livewire\Menu\Warehouse;
use App\Livewire\Menu\WarehouseStock;

Route::prefix('master-management')->name('master-management.')->group(function () {
    Route::get('/suppliers', Supplier::class)->name('suppliers')->middleware('permission:view supplier');
    Route::get('/customers', Customer::class)->name('customers')->middleware('permission:view customer');
    Route::get('/product-categories', ProductCategory::class)->name('product-categories')->middleware('permission:view category');
    Route::get('/product-prices', ProductPrice::class)->name('product-prices')->middleware('permission:view price');
    Route::get('/products', Product::class)->name('products')->middleware('permission:view product');
    Route::get('/warehouses', Warehouse::class)->name('warehouses')->middleware('permission:view warehouse');
    Route::get('/warehouse-stocks', WarehouseStock::class)->name('warehouse-stocks')->middleware('permission:view stock');
});
