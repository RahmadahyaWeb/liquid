<?php

use App\Livewire\Menu\SalesInvoice;
use App\Livewire\Menu\SalesInvoiceDetail;
use App\Livewire\Menu\SalesOrder;
use App\Livewire\Menu\SalesOrderDetails;

Route::prefix('sales-management')->name('sales-management.')->group(function () {
    Route::get('/sales-orders', SalesOrder::class)->name('sales-orders')->middleware('permission:view sales-order');
    Route::get('/sales-orders/detail/{id}', SalesOrderDetails::class)->name('sales-orders-detail')->middleware('permission:view sales-order-detail');
    Route::get('/sales-invoices', SalesInvoice::class)->name('sales-invoices')->middleware('permission:view sales-invoice');
    Route::get('/sales-invoices/detail/{id}', SalesInvoiceDetail::class)->name('sales-invoices-detail')->middleware('permission:view sales-invoice-detail');
});
