<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\EmisiCarbonController;
use App\Http\Controllers\FaktorEmisiController;
use App\Http\Controllers\KompensasiEmisiController;

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

<<<<<<< HEAD
// Route untuk Kompensasi Emisi
Route::resource('kompensasi', KompensasiEmisiController::class)
    ->except(['create'])
    ->names([
    'index' => 'manager.kompensasi.index',
    'store' => 'manager.kompensasi.store',
    'show' => 'manager.kompensasi.show',
    'edit' => 'manager.kompensasi.edit',
    'update' => 'manager.kompensasi.update',
    'destroy' => 'manager.kompensasi.destroy'
]);

// Route untuk Emisi Karbon
Route::resource('emisicarbon', EmisiCarbonController::class);
=======
// Route default langsung ke dashboard
Route::get('/', function () {
    return view('dashboard');
});

// Kalau kamu juga mau akses via /dashboard, bisa tambah ini:
Route::get('/dashboard', function () {
    return view('dashboard');
});

// Tambahkan route baru untuk history
Route::get('/history', function () {
    return view('history');
});

Route::post('/emissions', function (Request $request) {
    // Handle form submission untuk create/update
    // Ini contoh saja, nanti diimplementasikan oleh backend
    return response()->json(['success' => true]);
});

Route::delete('/emissions/{id}', function ($id) {
    // Handle delete
    // Ini contoh saja, nanti diimplementasikan oleh backend
    return response()->json(['success' => true]);
});
>>>>>>> origin/main
