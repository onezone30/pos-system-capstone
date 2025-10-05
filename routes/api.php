<?php

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

// Example API route
Route::get('/hello', function () {
    return response()->json(['message' => 'API working!']);
});

// Authentication
Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [LoginController::class, 'apiLogin'])->middleware('guest');
// Route::post('/login', [LoginController::class, 'store']);
Route::delete('/logout', [LoginController::class, 'logout'])->middleware(['auth:sanctum', 'auth']);

// Example protected route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::middleware('auth:sanctum')->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});