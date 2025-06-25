<?php

use Azuriom\Plugin\ShopEasyReg\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware('can:admin')->group(function () {
    Route::get('/', [SettingsController::class, 'show'])->name('index');
    Route::post('/', [SettingsController::class, 'save'])->name('save');
});
