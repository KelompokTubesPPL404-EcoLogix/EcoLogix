<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\EmisiKarbonController;
use App\Http\Controllers\FaktorEmisiController;
use App\Http\Controllers\KompensasiEmisiController;
use App\Http\Controllers\PembelianCarbonCreditController;
use App\Http\Controllers\CarbonCreditPurchaseController;
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

// Route API untuk notifikasi (tersedia untuk semua user yang sudah login)
Route::middleware('auth')->group(function () {
    // Route untuk mendapatkan notifikasi via AJAX
    Route::get('/api/notifikasi', [App\Http\Controllers\NotifikasiController::class, 'getNotifikasi'])->name('notifikasi.get');
    
    // Route untuk menandai notifikasi sebagai dibaca
    Route::post('/api/notifikasi/mark-as-read', [App\Http\Controllers\NotifikasiController::class, 'markAsRead'])->name('notifikasi.markAsRead');
});

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
        
        // Leaderboard perusahaan
        Route::get('/leaderboard', [App\Http\Controllers\LeaderboardController::class, 'superAdminLeaderboard'])->name('leaderboard');
        
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
        
        // Leaderboard perusahaan
        Route::get('/leaderboard', [App\Http\Controllers\LeaderboardController::class, 'managerLeaderboard'])->name('leaderboard');
        
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
        
        // Notifikasi
        Route::get('/notifikasi', [App\Http\Controllers\NotifikasiController::class, 'getNotifikasi'])->name('notifikasi.index');
        
        
        
        // Faktor Emisi
        Route::resource('faktor-emisi', FaktorEmisiController::class);
        
        // // Pembelian Carbon Credit (Original Controller)
        // Route::resource('pembelian-carbon-credit', PembelianCarbonCreditController::class);
        
        // // Route for auto-populating form fields
        // Route::get('pembelian-carbon-credit-get-form-data', [PembelianCarbonCreditController::class, 'getFormData'])
        //     ->name('pembelian-carbon-credit.get-form-data');
        
        // Improved Carbon Credit Purchase Feature
        Route::resource('carbon-credit-purchase', CarbonCreditPurchaseController::class);
        
        // Additional routes for improved carbon credit purchase
        Route::get('carbon-credit-purchase-get-form-data', [CarbonCreditPurchaseController::class, 'getFormData'])
            ->name('carbon-credit-purchase.get-form-data');
        Route::get('carbon-credit-purchase-dashboard', [CarbonCreditPurchaseController::class, 'dashboard'])
            ->name('carbon-credit-purchase.dashboard');
        Route::get('carbon-credit-purchase-report', [CarbonCreditPurchaseController::class, 'report'])
            ->name('carbon-credit-purchase.report');
    });

    // Rute Staff
    Route::middleware(\App\Http\Middleware\CheckRole::class . ':staff')->prefix('staff')->name('staff.')->group(function () {
        // Staff dashboard
        Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'staff'])->name('dashboard');
        
        // Route untuk Emisi Karbon - Staff
        Route::resource('emisicarbon', EmisiKarbonController::class);
        
        // Notifikasi
        Route::get('/notifikasi', [App\Http\Controllers\NotifikasiController::class, 'getNotifikasi'])->name('notifikasi.index');
        
        
    });
    
    // Manager dapat juga mengakses dan memodifikasi data faktor emisi dan penyedia carbon credit
    Route::middleware(\App\Http\Middleware\CheckRole::class . ':manager')->prefix('manager')->name('manager.')->group(function () {
        Route::resource('faktor-emisi', FaktorEmisiController::class);
        Route::resource('penyedia-carbon-credit', PenyediaCarbonCreditController::class);
        
        // Notifikasi
        Route::get('/notifikasi', [App\Http\Controllers\NotifikasiController::class, 'getNotifikasi'])->name('notifikasi.index');
        
        
    });
    
    // Tambahkan route untuk history
    Route::get('/history', function () {
        return view('history');
    });
    
    // Tidak menggunakan API lagi untuk dashboard data
});