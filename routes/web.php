<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\EmisiCarbonController;
use App\Http\Controllers\FaktorEmisiController;

Route::get('/', function () {
    return view('welcome');
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
