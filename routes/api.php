<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GameApiController; // <--- Pastikan ini ada

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// TAMBAHKAN BARIS INI:
Route::get('/games', [GameApiController::class, 'index']);
Route::get('/banners', [GameApiController::class, 'getBanners']);
Route::get('/payments', [GameApiController::class, 'getPaymentMethods']);