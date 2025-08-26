<?php

use App\Http\Controllers\ChartController;
use App\Livewire\Auth\Login;
use App\Livewire\Menu\ConfigApp;
use App\Livewire\Menu\Dashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard')->middleware('permission:view dashboard');
    Route::get('/chart/penjualan', [ChartController::class, 'loadPenjualanChart']);

    // CONFIG
    Route::get('/config', ConfigApp::class)->name('config')->middleware('permission:view config');

    require __DIR__ . '/user-management/user-management.php';
    require __DIR__ . '/master-management/master-management.php';
    require __DIR__ . '/purchase-management/purchase-management.php';
    require __DIR__ . '/fifo-management/fifo-management.php';
    require __DIR__ . '/sales-management/sales-management.php';
    require __DIR__ . '/report/report.php';

    Route::get('/logout', function () {
        Auth::logout();

        return redirect('/');
    });
});

Route::middleware(['guest'])->group(function () {
    Route::get('login', Login::class)->name('login');
});
