<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PembelianCarbonCreditController;


// Redirect root URL ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Routes untuk autentikasi
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Routes untuk register
    Route::get('register/pengguna', [RegisterController::class, 'showPenggunaRegisterForm'])->name('register.pengguna');
    Route::post('register/pengguna', [RegisterController::class, 'registerPengguna']);

    Route::get('register/admin', [RegisterController::class, 'showAdminRegisterForm'])->name('register.admin');
    Route::post('register/admin', [RegisterController::class, 'registerAdmin']);

    Route::get('register/manager', [RegisterController::class, 'showManagerRegisterForm'])->name('register.manager');
    Route::post('register/manager', [RegisterController::class, 'registerManager']);

    //jadi bikin ngk nih?
    Route::get('register/super_admin', [RegisterController::class, 'showSuperAdminRegisterForm'])->name('register.super_admin');
    Route::post('register/super_admin', [RegisterController::class, 'registerSuperAdmin'])->name('register.super_admin.submit');
    Route::post('register/super_admin', [RegisterController::class, 'registerSuperAdmin']);
});

// Routes untuk Pengguna yang sudah login
Route::middleware(['auth:super_admin'])->group(function () {
    Route::get('/super_admin/dashboard', [DashboardController::class, 'super_adminDashboard'])->name('super_admin.dashboard');
    
    // Gunakan prefix untuk konsistensi path
    Route::prefix('super_admin')->group(function () {

    });
});

// Routes untuk Pengguna yang sudah login
Route::middleware(['auth:pengguna'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'userDashboard'])
             ->name('user.dashboard');
});

// Routes untuk Admin yang sudah login
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    
    // Gunakan prefix untuk konsistensi path
    Route::prefix('admin')->group(function () {
        // Rute untuk mengelola emisi karbon


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

        // Route untuk laporan emisi karbon
        Route::get('/emisicarbon/list-report', [EmisiCarbonController::class, 'listReport'])
            ->name('admin.emissions.list_report');
        Route::get('/emissions/selected-report', [EmisiCarbonController::class, 'downloadSelectedReport'])
            ->name('admin.emissions.selected.report');
        Route::get('/emisicarbon/report', [EmisiCarbonController::class, 'downloadReport'])
            ->name('admin.emissions.report');

        // Route untuk laporan pembelian carbon credit
        Route::get('/carbon_credit/list-report', [PembelianCarbonCreditController::class, 'listReport'])
            ->name('carbon_credit.list_report');
        Route::get('/carbon_credit/report', [PembelianCarbonCreditController::class, 'downloadSelectedReport'])
            ->name('carbon_credit.report');

    });
});
// Routes untuk Manager yang sudah login
Route::middleware(['auth:manager'])->group(function () {
    Route::prefix('manager')->group(function () {
        // Dashboard route
        Route::get('/dashboard', [DashboardController::class, 'managerDashboard'])
             ->name('manager.dashboard');


                    // Routes untuk Carbon Credit
        Route::get('/carbon-credit', [PembelianCarbonCreditController::class, 'managerIndex'])
        ->name('manager.carbon_credit.index');


    });
});

// Route untuk logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Tambahkan rute berikut
Route::middleware(['auth:pengguna,manager,admin'])->group(function () {
});