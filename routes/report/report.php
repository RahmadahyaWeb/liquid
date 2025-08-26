<?php

use App\Livewire\Menu\Report\ReportAr;
use App\Livewire\Menu\Report\ReportFifo;
use App\Livewire\Menu\Report\ReportProducts;
use App\Livewire\Menu\Report\ReportPurchases;
use App\Livewire\Menu\Report\ReportSales;
use App\Livewire\Menu\Report\ReportStocks;

Route::prefix('report')
    ->name('report.')
    ->group(function () {
        Route::get('/report-products', ReportProducts::class)->name('report-products')->middleware('permission:view report-produk');
        Route::get('/report-stocks', ReportStocks::class)->name('report-stocks')->middleware('permission:view report-stok');
        Route::get('/report-sales', ReportSales::class)->name('report-sales')->middleware('permission:view report-penjualan');
        Route::get('/report-purchases', ReportPurchases::class)->name('report-purchases')->middleware('permission:view report-pembelian');
        Route::get('/report-fifo', ReportFifo::class)->name('report-fifo')->middleware('permission:view report-fifo');
        Route::get('/report-ar', ReportAr::class)->name('report-ar')->middleware('permission:view report-ar');
    });
