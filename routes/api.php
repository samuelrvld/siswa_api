<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

// Route untuk registrasi
Route::post('/register', [AuthController::class, 'register']);

// Route untuk login
Route::post('/login', [AuthController::class, 'login']);

// Menggunakan middleware auth:sanctum untuk melindungi route siswa
Route::middleware('auth:sanctum')->group(function () {
    // Route untuk operasi CRUD pada resource siswa
    Route::resource('siswa', SiswaController::class);
});
