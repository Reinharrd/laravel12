<?php

use Illuminate\Support\Facades\Route;
use Modules\Api\Controllers\AuthController;
use Modules\Api\Controllers\KoleksiController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/koleksi', [KoleksiController::class, 'getDataKoleksi']);
    Route::post('/koleksi/tambah', [KoleksiController::class, 'tambahKoleksi']);
    Route::put('/koleksi/update/{id}', [KoleksiController::class, 'updateKoleksi']);
    Route::delete('/koleksi/delete/{id}', [KoleksiController::class, 'deleteKoleksi']);
});
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});
