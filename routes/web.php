<?php

use App\Http\BerandaController\BerandaController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PetaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('kalteng');
});

// Route untuk halaman peta
Route::get('/kalteng', [PetaController::class, 'index']);
Route::get('/produktivitas', [PetaController::class, 'Pduktiv']);
Route::get('/luasPanen', [PetaController::class, 'Lpanen']);
Route::get('/padi', [PetaController::class, 'Padi']);
Route::get('/beras', [PetaController::class, 'Beras']);
Route::get('/Analisis', [PetaController::class, 'statistik']);
Route::get('/interaktif', [PetaController::class, 'Interaktif']);

Route::get('/api/kalteng.json', [PetaController::class, 'getData']);
