<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\auth\ForgotPasswordController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\PasswordController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function() {


    return view('welcome');
})
    ->name('home');

Route::middleware(['guest'])->group(function() {
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'store'])
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
    Route::post('/reset-password', [PasswordController::class, 'store'])
        ->name('password.update');

});

Route::get('/logout', [LoginController::class, 'destroy'])
        ->name('logout')
        ->middleware('auth');

Route::middleware('auth')->group(function() {
    Route::get('/profile/{userId}', [ProfileController::class, 'create']);
});

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
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
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
    Route::post('/users', [UserController::class, 'store']);
    Route::patch('/users/{user}', [UserController::class, 'update'])
        ->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->name('admin.users.destroy');
    Route::get('/users', SearchController::class)
        ->name('admin.users.search');

    // categories
    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('admin.categories');
    Route::post('/categories', [CategoryController::class, 'store'])
        ->name('admin.categories.store');
    Route::patch('/categories/{category}', [CategoryController::class, 'update'])
        ->name('admin.categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
        ->name('admin.categories.destroy');

    // orders
    Route::get('/orders', [OrderController::class, 'index'])
        ->name('admin.orders');
    Route::get('/orders/create', [OrderController::class, 'create'])
        ->name('admin.orders.create');

    Route::get('/sales',[ SalesController::class, 'index'])
        ->name('admin.sales');
});


// cashier
Route::middleware(['auth', 'role:cashier'])->prefix('cashier')->group(function() {
    Route::get('/dashboard', [CashierController::class, 'index'])
        ->name('cashier.dashboard');
});
