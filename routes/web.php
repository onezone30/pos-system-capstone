<?php

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


Route::middleware(['auth', 'role:admin'])->group(function() {
    
});

Route::middleware(['auth', 'role:customer'])->group(function() {
        Route::get('/dashboard', function() {
        return view('customer.dashboard', [
            'user' => Auth::user()
        ]   );
        })->name('dashboard')->middleware(['auth']);
});

Route::middleware(['auth', 'role:cashier'])->group(function() {
    
});

Route::get('/logout', function() {
    Auth::logout();

    return view('login');
});