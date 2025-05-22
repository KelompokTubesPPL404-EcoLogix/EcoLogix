<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\EmisiKarbonController;
use App\Http\Controllers\FaktorEmisiController;
use App\Http\Controllers\KompensasiEmisiController;
use App\Http\Controllers\PembelianCarbonCreditController;
use App\Http\Controllers\PenyediaCarbonCreditController;
use App\Http\Controllers\StaffController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Route default ke home
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        switch ($user->role) {
            case 'super_admin':
                return redirect()->route('superadmin.dashboard');
            case 'manager':
                return redirect()->route('manager.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'staff':
                return redirect()->route('staff.dashboard');
            default:
                return view('welcome');
        }
    }
    return redirect()->route('login');
})->name('home');

// Dashboard redirect
Route::get('/dashboard', function () {
    return redirect()->route('home');
});

// Rute untuk autentikasi
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
    
    // Route untuk memeriksa apakah email adalah Super Admin
    Route::get('/check-super-admin', [App\Http\Controllers\AuthController::class, 'checkSuperAdmin'])->name('check.super-admin');
    
    // Register Super Admin (hanya tersedia ketika belum ada super admin)
    Route::get('/register/super-admin', function () {
        if (\App\Models\User::where('role', 'super_admin')->count() > 0) {
            return redirect()->route('login');
        }
        return view('auth.register-super-admin');
    })->name('register.super-admin');
    Route::post('/register/super-admin', [App\Http\Controllers\AuthController::class, 'registerSuperAdmin'])->name('register.super-admin.post');
});

// Rute untuk user yang sudah terotentikasi
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    // Rute Super Admin
    Route::middleware(\App\Http\Middleware\CheckRole::class . ':super_admin')->prefix('super-admin')->name('superadmin.')->group(function () {
        // Super Admin dashboard
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'superAdmin'])->name('dashboard');
        
        // Manajemen perusahaan
        Route::resource('perusahaan', PerusahaanController::class);
        
        // Manajemen manager
        // Manajemen manager menggunakan ManagerController
        Route::resource('manager', App\Http\Controllers\ManagerController::class)->parameters(['manager' => 'manager']); // Parameter diubah ke 'manager' untuk route model binding User
        // Rute tambahan jika diperlukan, misalnya untuk menampilkan form create dengan kode perusahaan tertentu
        Route::get('/manager/create/for-perusahaan/{kode_perusahaan}', [App\Http\Controllers\ManagerController::class, 'create'])->name('superadmin.manager.create.for_perusahaan');
    });

    // Rute Manager
    Route::middleware(\App\Http\Middleware\CheckRole::class . ':manager')->prefix('manager')->name('manager.')->group(function () {
        // Manager dashboard
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'manager'])->name('dashboard');
        
        // Manajemen admin menggunakan AdminController
        Route::resource('admin', App\Http\Controllers\AdminController::class)->parameters(['admin' => 'admin']); // Parameter diubah ke 'admin' untuk route model binding User
        
        // Manajemen staff
        Route::get('/staff', function () {
            $staffs = \App\Models\User::where('role', 'staff')
                                     ->where('kode_perusahaan', Auth::user()->kode_perusahaan)
                                     ->get();
            return view('manager.staff.index', compact('staffs'));
        })->name('staff.index');
        Route::get('/staff/create', function () {
            return view('manager.staff.create');
        })->name('staff.create');
        Route::post('/staff', [App\Http\Controllers\AuthController::class, 'registerStaff'])->name('staff.store');
        
        // Route untuk Kompensasi Emisi
        Route::resource('kompensasi', KompensasiEmisiController::class);
        Route::get('/kompensasi-report', [KompensasiEmisiController::class, 'report'])->name('kompensasi.report');
    });

    // Rute Admin
    Route::middleware(\App\Http\Middleware\CheckRole::class . ':admin')->prefix('admin')->name('admin.')->group(function () {
        // Admin dashboard
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'admin'])->name('dashboard');
        
        // Manajemen staff menggunakan StaffController
        Route::resource('staff', App\Http\Controllers\StaffController::class)->parameters(['staff' => 'staff']); 
        
        // Emisi Karbon - Admin (review dan approval)
        Route::get('/emisicarbon', [EmisiKarbonController::class, 'adminIndex'])->name('emisicarbon.index');
        Route::get('/emisicarbon/{emisicarbon}', [EmisiKarbonController::class, 'show'])->name('emisicarbon.show');
        Route::get('/emisicarbon/{emisicarbon}/edit-status', [EmisiKarbonController::class, 'editStatus'])->name('emisicarbon.editStatus');
        Route::put('/emisicarbon/{emisicarbon}/update-status', [EmisiKarbonController::class, 'updateStatus'])->name('emisicarbon.updateStatus');
        
        // Faktor Emisi
        Route::resource('faktor-emisi', FaktorEmisiController::class);
    });

    // Rute Staff
    Route::middleware(\App\Http\Middleware\CheckRole::class . ':staff')->prefix('staff')->name('staff.')->group(function () {
        // Staff dashboard
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'staff'])->name('dashboard');
        
        // Route untuk Emisi Karbon - Staff
        Route::resource('emisicarbon', EmisiKarbonController::class);
    });
    
    // Manager dapat juga mengakses dan memodifikasi data faktor emisi dan penyedia carbon credit
    Route::middleware(\App\Http\Middleware\CheckRole::class . ':manager')->prefix('manager')->name('manager.')->group(function () {
        Route::resource('faktor-emisi', FaktorEmisiController::class);
        Route::resource('penyedia-carbon-credit', PenyediaCarbonCreditController::class);
    });

    // CRUD Pembelian Carbon Credit
    Route::resource('carbon_credit', PembelianCarbonCreditController::class)
        ->except(['show'])
        ->names([
            'index' => 'carbon_credit.index',
            'create' => 'carbon_credit.create', 
            'store' => 'carbon_credit.store',
            'edit' => 'carbon_credit.edit',
            'update' => 'carbon_credit.update',
            'destroy' => 'carbon_credit.destroy'
        ]);

    // Edit Status Pembelian Carbon Credit
    Route::get('/carbon_credit/{kode_pembelian_carbon_credit}/edit-status', [PembelianCarbonCreditController::class, 'editStatus'])
        ->name('carbon_credit.edit_status');
    Route::put('/carbon_credit/{kode_pembelian_carbon_credit}/update-status', [PembelianCarbonCreditController::class, 'updateStatus'])
        ->name('carbon_credit.update_status');

    // Route untuk laporan pembelian carbon credit
    Route::get('/carbon_credit/list-report', [PembelianCarbonCreditController::class, 'listReport'])
        ->name('carbon_credit.list_report');
    Route::get('/carbon_credit/report', [PembelianCarbonCreditController::class, 'downloadSelectedReport'])
        ->name('carbon_credit.report');
    
    // Tambahkan route untuk history
    Route::get('/history', function () {
        return view('history');
    });
    
    // Tidak menggunakan API lagi untuk dashboard data
});