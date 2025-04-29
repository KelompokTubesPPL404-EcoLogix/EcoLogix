<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\EmisiCarbonController;
use App\Http\Controllers\FaktorEmisiController;
use App\Http\Controllers\KompensasiEmisiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route untuk Perusahaan
// Route::get('/perusahaan', [PerusahaanController::class, 'index'])->name('perusahaan.index');
// Route::get('/perusahaan/create', [PerusahaanController::class, 'create'])->name('perusahaan.create');
// Route::post('/perusahaan', [PerusahaanController::class, 'store'])->name('perusahaan.store');
// Route::get('/perusahaan/{kode_perusahaan}', [PerusahaanController::class, 'show'])->name('perusahaan.show');
// Route::get('/perusahaan/{kode_perusahaan}/edit', [PerusahaanController::class, 'edit'])->name('perusahaan.edit');
// Route::put('/perusahaan/{kode_perusahaan}', [PerusahaanController::class, 'update'])->name('perusahaan.update');
// Route::delete('/perusahaan/{kode_perusahaan}', [PerusahaanController::class, 'destroy'])->name('perusahaan.destroy');


// Routes untuk autentikasi
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Routes untuk register
    Route::get('register/staff', [RegisterController::class, 'showStaffRegisterForm'])->name('register.staff');
    Route::post('register/staff', [RegisterController::class, 'registerStaff']);

    Route::get('register/admin', [RegisterController::class, 'showAdminRegisterForm'])->name('register.admin');
    Route::post('register/admin', [RegisterController::class, 'registerAdmin']);

    Route::get('register/manager', [RegisterController::class, 'showManagerRegisterForm'])->name('register.manager');
    Route::post('register/manager', [RegisterController::class, 'registerManager']);

    //jadi bikin ngk nih?
    Route::get('register/super_admin', [RegisterController::class, 'showSuperAdminRegisterForm'])->name('register.super_admin');
    Route::post('register/super_admin', [RegisterController::class, 'registerSuperAdmin'])->name('register.super_admin.submit');
    Route::post('register/super_admin', [RegisterController::class, 'registerSuperAdmin']);
});

// Routes untuk super admin yang sudah login
Route::middleware(['auth:super_admin'])->group(function () {
    Route::get('/super_admin/dashboard', [DashboardController::class, 'super_adminDashboard'])->name('super_admin.dashboard');
    
    // Gunakan prefix untuk konsistensi path
    Route::prefix('super_admin')->group(function () {

    });
});

// Routes untuk staff yang sudah login
Route::middleware(['auth:staff'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'staffDashboard'])
             ->name('staff.dashboard');
});

// Routes untuk Admin yang sudah login
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    
    // Gunakan prefix untuk konsistensi path
    Route::prefix('admin')->group(function () {
        // Rute untuk mengelola emisi karbon

    });
});
// Routes untuk Manager yang sudah login
Route::middleware(['auth:manager'])->group(function () {
    Route::prefix('manager')->group(function () {
        // Dashboard route
        Route::get('/dashboard', [DashboardController::class, 'managerDashboard'])
             ->name('manager.dashboard');

    });
});

// Route untuk logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Tambahkan rute berikut
Route::middleware(['auth:staff,manager,admin'])->group(function () {
});


// // Faktor Emisi Routes
// Route::resource('faktor-emisi', FaktorEmisiController::class);

// // Route untuk Kompensasi Emisi
// Route::resource('kompensasi', KompensasiEmisiController::class)
//     ->except(['create'])
//     ->names([
//     'index' => 'manager.kompensasi.index',
//     'store' => 'manager.kompensasi.store',
//     'show' => 'manager.kompensasi.show',
//     'edit' => 'manager.kompensasi.edit',
//     'update' => 'manager.kompensasi.update',
//     'destroy' => 'manager.kompensasi.destroy'
// ]);

// // Route untuk Emisi Karbon
// Route::resource('emisicarbon', EmisiCarbonController::class);
// // Route untuk history
// Route::get('/history', function () {
//     return view('history');
// });



// Route::post('/emissions', function (Request $request) {
//     // Handle form submission untuk create/update
//     // Ini contoh saja, nanti diimplementasikan oleh backend
//     return response()->json(['success' => true]);
// });

// Route::delete('/emissions/{id}', function ($id) {
//     // Handle delete
//     // Ini contoh saja, nanti diimplementasikan oleh backend
//     return response()->json(['success' => true]);
// });
