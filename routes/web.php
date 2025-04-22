<?php

use Illuminate\Support\Facades\Route;

// Halaman dashboard
Route::get('/', function () {
    return view('manager.dashboard');
})->name('manager.dashboard');

// Halaman Kompensasi
Route::prefix('kompensasi')->group(function () {
    Route::get('/', function () {
        return view('manager.kompensasi.index');
    })->name('manager.kompensasi.index');

    Route::get('/{id}', function ($id) {
        return view('manager.kompensasi.show', ['id' => $id]);
    })->name('manager.kompensasi.show');

    Route::get('/{id}/edit', function ($id) {
        return view('manager.kompensasi.edit', ['id' => $id]);
    })->name('manager.kompensasi.edit.');

    Route::get('/report/pdf', function () {
        return view('manager.kompensasi.report');
    })->name('manager.kompensasi.report');
});

// Halaman Faktor Emisi
Route::get('/faktor-emisi', function () {
    return view('manager.faktor-emisi.index');
})->name('manager.faktor-emisi.index');

// Halaman Penyedia Carbon Credit
Route::get('/penyedia-carbon-credit', function () {
    return view('manager.penyedia.index');
})->name('manager.penyedia.index');

// Halaman Carbon Credit (daftar pembelian)
Route::get('/carbon-credit', function () {
    return view('manager.carbon_credit.index');
})->name('manager.carbon_credit.index');

// Halaman Profile Manager
Route::get('/profile', function () {
    return view('manager.profile');
})->name('manager.profile');