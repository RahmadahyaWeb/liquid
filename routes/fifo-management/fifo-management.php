<?php

use App\Livewire\Menu\FifoLayer;
use App\Livewire\Menu\FifoUsage;

Route::prefix('fifo-management')->name('fifo-management.')->group(function () {
    Route::get('/fifo-layers', FifoLayer::class)->name('fifo-layers')->middleware('permission:view fifo-layers');
    Route::get('/fifo-usages', FifoUsage::class)->name('fifo-usages')->middleware('permission:view fifo-usages');
});
