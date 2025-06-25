<?php

use Azuriom\Plugin\ShopEasyReg\Controllers\CartAuthController;
use Illuminate\Support\Facades\Route;

// Регистрация из корзины
Route::prefix('shop/cart')->name('shop.cart.')->group(function () {
    Route::post('/register', [CartAuthController::class, 'register'])->name('register');
});
