<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




Route::get('/', [LoginController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::get('/register', [UserController::class, 'create'])
    ->middleware('guest');

Route::post('/register', [UserController::class, 'store'])
    ->middleware('guest');
Route::post('/login', [LoginController::class, 'store'])
    ->middleware('guest');


// customer
Route::middleware(['auth', 'role:customer'])->prefix('customer')->group(function() {
    Route::get('/dashboard', [CustomerController::class, 'index'])
        ->name('customer.dashboard');

    Route::get('/products', function() {
        dd('customer product');
    });

    // user
    Route::post('/users', function() {
        dd('customer user');
    })->name('cashier.user');
        
});


// admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function() {
    Route::get('/dashboard', [AdminController::class, 'index'])
        ->name('admin.dashboard');

    // products
    Route::get('/products', [ProductController::class, 'index'])
        ->name('admin.products');
    Route::post('/products', [ProductController::class, 'store'])
        ->name('admin.products.store');
    Route::patch('/products/{product}', [ProductController::class, 'update'])
        ->name('admin.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->name('admin.products.destroy');


    // users
    Route::get('/users', [UserController::class, 'index'])
        ->name('admin.users');
    Route::patch('/users/{user}', [UserController::class, 'update'])
        ->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('admin.users.destroy');
});


// cashier
Route::middleware(['auth', 'role:cashier'])->prefix('cashier')->group(function() {
    Route::get('/dashboard', [CashierController::class, 'index'])
        ->name('cashier.dashboard');
});

Route::get('/logout', [LoginController::class, 'destroy']);