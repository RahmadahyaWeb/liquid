<?php

use App\Livewire\Menu\Role;
use App\Livewire\Menu\User;

Route::prefix('user-management')->name('user-management.')->group(function () {
    Route::get('/user', User::class)->name('users')->middleware('permission:view user');
    Route::get('/role', Role::class)->name('roles')->middleware('permission:view role');
});
