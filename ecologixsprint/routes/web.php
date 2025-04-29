<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Redirect root URL ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

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