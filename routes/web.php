<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterUserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('/login', [LoginController::class, 'create']);
Route::get('/register', [RegisterUserController::class, 'create']);

Route::post('/register', [RegisterUserController::class, 'store']);
Route::post('/login', [LoginController::class, 'store'])->name('login');

Route::middleware(['auth', 'role:customer'])->prefix('customer')->group(function() {
    Route::get('/dashboard', [CustomerController::class, 'index'])
        ->name('customer.dashboard');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function() {
    Route::get('/dashboard', [AdminController::class, 'index'])
        ->name('admin.dashboard');
});

Route::middleware(['auth', 'role:cashier'])->prefix('cashier')->group(function() {
    Route::get('/dashboard', [CashierController::class, 'index'])
        ->name('cashier.dashboard');
});

Route::get('/logout', [LoginController::class, 'destroy']);