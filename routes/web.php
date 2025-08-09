<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('/login', [LoginController::class, 'create']);
Route::get('/register', [RegisterUserController::class, 'create']);

Route::post('/register', [RegisterUserController::class, 'store']);
Route::post('/login', [LoginController::class, 'store']);

Route::get('/dashboard', function() {
    return view('customer.dashboard');
})->name('dashboard');