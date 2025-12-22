<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\auth\ForgotPasswordController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\PasswordController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', [LoginController::class, 'create'])
    ->name('login');

Route::middleware(['guest'])->group(function() {

    Route::post('/', [LoginController::class, 'store'])
        ->name('login.store');

    Route::get('/register', [UserController::class, 'create'])
        ->name('register');
    Route::post('/register', [UserController::class, 'store'])
        ->name('register.store');



    // forgot password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
        ->name('forgot-password');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
        ->middleware('guest')
        ->name('forgot-password.email');

    // reset password
    Route::get('/reset-password/{token}', [PasswordController::class, 'create'])
        ->name('reset-password');
    Route::post('/reset-password/{token}', [PasswordController::class, 'store'])
        ->name('password.update');

});

Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout')
        ->middleware('auth');
        
Route::middleware('auth')->group(function() {
    Route::get('/profile/{id}', [ProfileController::class, 'show'])
        ->name('profile');
    Route::patch('/profile/{id}', [ProfileController::class, 'update'])
        ->name('profile.update');
});

// owner
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function() {
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/activity-log', [OwnerController::class, 'activityLog'])
        ->name('activity-log');

    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders');
    Route::get('/orders/create', [OrderController::class, 'create'])
        ->name('orders.create');

    Route::get('/sales',[ SalesController::class, 'index'])
        ->name('sales');

    Route::get('/inventory', [InventoryController::class, 'index'])
        ->name('inventory');
});


// admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/activity-log', [AdminController::class, 'activityLog'])
        ->name('activity-log');

    // products
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products'); 
    Route::post('/products', [ProductController::class, 'store'])
        ->name('products.store');
    Route::patch('/products/{product}', [ProductController::class, 'update'])
        ->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->name('products.destroy');


    // users
    Route::get('/users', [UserController::class, 'index'])
        ->name('users');
    Route::post('/users', [UserController::class, 'store']);
    Route::patch('/users/{user}', [UserController::class, 'update'])
        ->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('users.destroy');
    // Route::get('/users', SearchController::class)
    //     ->name('users.search');

    // categories
    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('categories');
    Route::post('/categories', [CategoryController::class, 'store'])
        ->name('categories.store');
    Route::patch('/categories/{category}', [CategoryController::class, 'update'])
        ->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy');

    // orders
    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders');
    Route::get('/orders/create', [OrderController::class, 'create'])
        ->name('orders.create');


    Route::get('/sales',[ SalesController::class, 'index'])
        ->name('sales');

    Route::get('/inventory', [InventoryController::class, 'index'])
        ->name('inventory');
});
Route::get('/orders/{id}/print', [OrderController::class, 'print'])
        ->name('orders.print');

// cashier
Route::middleware(['auth', 'role:cashier'])->prefix('cashier')->name('cashier.')->group(function() {
    Route::get('/dashboard', [CashierController::class, 'index'])->name('dashboard');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
});


