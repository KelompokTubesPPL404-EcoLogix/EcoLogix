<?php

use Illuminate\Support\Facades\Route;

// Route default langsung ke dashboard
Route::get('/', function () {
    return view('dashboard');
});

// Kalau kamu juga mau akses via /dashboard, bisa tambah ini:
Route::get('/dashboard', function () {
    return view('dashboard');
});
