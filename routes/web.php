<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\EmisiCarbonController;
use App\Http\Controllers\FaktorEmisiController;
use App\Http\Controllers\KompensasiEmisiController;
use App\Http\Controllers\PembelianCarbonCreditController;
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
        Route::get('/dashboard', function () {
            return view('super-admin.dashboard');
        })->name('dashboard');
        
        // Manajemen perusahaan
        Route::get('/perusahaan', function () {
            $perusahaan = \App\Models\Perusahaan::all();
            return view('super-admin.perusahaan.index', compact('perusahaan'));
        })->name('perusahaan.index');
        Route::get('/perusahaan/create', function () {
            return view('super-admin.perusahaan.create');
        })->name('perusahaan.create');
        Route::post('/perusahaan', [App\Http\Controllers\AuthController::class, 'registerPerusahaan'])->name('perusahaan.store');
        
        // Manajemen manager
        Route::get('/manager/create/{kode_perusahaan}', function ($kode_perusahaan) {
            $perusahaan = \App\Models\Perusahaan::where('kode_perusahaan', $kode_perusahaan)->firstOrFail();
            return view('super-admin.manager.create', compact('perusahaan'));
        })->name('manager.create');
        Route::post('/manager', [App\Http\Controllers\AuthController::class, 'registerManager'])->name('manager.store');
    });

    // Rute Manager
    Route::middleware(\App\Http\Middleware\CheckRole::class . ':manager')->prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', function () {
            return view('manager.dashboard');
        })->name('dashboard');
        
        // Manajemen admin
        Route::get('/admin', function () {
            $admins = \App\Models\User::where('role', 'admin')
                                     ->where('kode_perusahaan', Auth::user()->kode_perusahaan)
                                     ->get();
            return view('manager.admin.index', compact('admins'));
        })->name('admin.index');
        Route::get('/admin/create', function () {
            return view('manager.admin.create');
        })->name('admin.create');
        Route::post('/admin', [App\Http\Controllers\AuthController::class, 'registerAdmin'])->name('admin.store');
        
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
        Route::resource('kompensasi', KompensasiEmisiController::class)
            ->except(['create']);
    });

    // Rute Admin
    Route::middleware(\App\Http\Middleware\CheckRole::class . ':admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        
        // Manajemen staff
        Route::get('/staff', function () {
            $staffs = \App\Models\User::where('role', 'staff')
                                     ->where('kode_perusahaan', Auth::user()->kode_perusahaan)
                                     ->get();
            return view('admin.staff.index', compact('staffs'));
        })->name('staff.index');
        Route::get('/staff/create', function () {
            return view('admin.staff.create');
        })->name('staff.create');
        Route::post('/staff', [App\Http\Controllers\AuthController::class, 'registerStaff'])->name('staff.store');
    });

    // Rute Staff
    Route::middleware(\App\Http\Middleware\CheckRole::class . ':staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', function () {
            return view('staff.dashboard');
        })->name('dashboard');
    });
    
    // Route untuk Perusahaan
    Route::resource('perusahaan', PerusahaanController::class);

    // Faktor Emisi Routes
    Route::resource('faktor-emisi', FaktorEmisiController::class);

    // Route untuk Emisi Karbon
    Route::resource('emisicarbon', EmisiCarbonController::class);

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
});