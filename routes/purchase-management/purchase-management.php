<?php

use App\Livewire\Menu\Purchase;
use App\Livewire\Menu\PurchaseDetail;
use App\Livewire\Menu\PurchaseReceipt;
use App\Livewire\Menu\PurchaseReceiptDetail;

Route::prefix('purchase-management')->name('purchase-management.')->group(function () {
    Route::get('/purchases', Purchase::class)->name('purchases')->middleware('permission:view purchase');
    Route::get('/purchases/detail/{id}', PurchaseDetail::class)->name('purchases-detail')->middleware('permission:view purchase-detail');
    Route::get('/purchases-receipts', PurchaseReceipt::class)->name('receipts')->middleware('permission:view receipt');
    Route::get('/purchases-receipts/detail/{id}', PurchaseReceiptDetail::class)->name('receipts-detail')->middleware('permission:view receipt-detail');
});
