<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;

// Halaman login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Halaman register untuk setiap role
Route::get('/register/admin', [RegisterController::class, 'showAdminRegisterForm'])->name('register.admin');
Route::get('/register/manager', [RegisterController::class, 'showManagerRegisterForm'])->name('register.manager');
Route::get('/register/staff', [RegisterController::class, 'showStaffRegisterForm'])->name('register.staff');

// Proses registrasi
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
