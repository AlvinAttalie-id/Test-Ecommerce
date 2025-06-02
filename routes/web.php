<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // ===================
    // Profile
    // ===================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.update.avatar');

    // ===================
    // Produk
    // ===================
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // ===================
    // Cart
    // ===================
    Route::get('/cart', [OrderController::class, 'cart'])->name('order.cart');
    Route::post('/cart/add', [OrderController::class, 'addToCart'])->name('order.cart.add');
    Route::delete('/cart/remove/{id}', [OrderController::class, 'removeFromCart'])->name('order.cart.remove');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
    Route::post('/order/store-cart', [OrderController::class, 'storeFromCart'])->name('order.store.cart');

    // ===================
    // Single Product Order
    // ===================
    Route::get('/order/{product}', [OrderController::class, 'create'])->name('order.create');
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');

    // ===================
    // Payment Callback (Midtrans)
    // ===================
    Route::post('/midtrans/callback', [PaymentController::class, 'handleCallback']);

    // ===================
    // Riwayat Transaksi
    // ===================
    Route::get('/transactions', [OrderController::class, 'history'])->name('transactions.history');
});

require __DIR__ . '/auth.php';
