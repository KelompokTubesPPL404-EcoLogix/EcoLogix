<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\EmisiCarbonController;
use App\Http\Controllers\FaktorEmisiController;

// Route default langsung ke dashboard
Route::get('/', function () {
    return view('dashboard');
});

// Kalau kamu juga mau akses via /dashboard, bisa tambah ini:
Route::get('/dashboard', function () {
    return view('dashboard');
});

// Route untuk Perusahaan
Route::get('/perusahaan', [PerusahaanController::class, 'index'])->name('perusahaan.index');
Route::get('/perusahaan/create', [PerusahaanController::class, 'create'])->name('perusahaan.create');
Route::post('/perusahaan', [PerusahaanController::class, 'store'])->name('perusahaan.store');
Route::get('/perusahaan/{kode_perusahaan}', [PerusahaanController::class, 'show'])->name('perusahaan.show');
Route::get('/perusahaan/{kode_perusahaan}/edit', [PerusahaanController::class, 'edit'])->name('perusahaan.edit');
Route::put('/perusahaan/{kode_perusahaan}', [PerusahaanController::class, 'update'])->name('perusahaan.update');
Route::delete('/perusahaan/{kode_perusahaan}', [PerusahaanController::class, 'destroy'])->name('perusahaan.destroy');

// Faktor Emisi Routes
Route::resource('faktor-emisi', FaktorEmisiController::class);
