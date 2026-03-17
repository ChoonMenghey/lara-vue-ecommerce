<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

// User Route

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('home');
    Route::get('/products', [UserController::class, 'productView'])->name('products');
});


// Add to cart route
Route:: prefix('cart')->controller(CartController::class)->group(function() {
    Route::get('/view', 'view')->name('cart.view');
    Route::post('store/{product}','store')->name('cart.store');
    Route::patch('update/{cartItem}','update')->name('cart.update');
    Route::delete('delete/{cartItem}','delete')->name('cart.delete');
});

// Payment route
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/history', [OrderController::class, 'history'])->name('order.history');

// Admin Route
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:web')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Products route (Admin)
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });
});

require __DIR__ . '/settings.php';
