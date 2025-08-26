<?php

use App\Livewire\Menu\Payment;
use App\Livewire\Menu\Receivable;

Route::prefix('ar-management')->name('ar-management.')->group(function () {
    Route::get('/receivables', Receivable::class)->name('receivables')->middleware('permission:view ar');
    Route::get('/payments', Payment::class)->name('payments')->middleware('permission:view pembayaran');
});
