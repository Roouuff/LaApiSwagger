<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;

// Memastikan semua request API otomatis meminta format JSON
Route::group(['middleware' => [\Illuminate\Routing\Middleware\SubstituteBindings::class]], function () {

    // 1. Endpoint Publik
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // 2. Endpoint Terproteksi
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        
        // CRUD Buku
        Route::apiResource('books', BookController::class);
    });

});





// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\AuthController;
// use App\Http\Controllers\Api\BookController;

// // 1. Endpoint Publik (Bisa diakses tanpa token/login)
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);

// // 2. Endpoint Terproteksi (Wajib menyertakan token Sanctum)
// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/me', [AuthController::class, 'me']);
//     Route::post('/logout', [AuthController::class, 'logout']);
    
//     // CRUD Buku otomatis mencakup index, store, show, update, destroy
//     Route::apiResource('books', BookController::class);
// });