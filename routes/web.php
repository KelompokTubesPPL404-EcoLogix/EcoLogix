<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\FaktorEmisiController;
use App\Http\Controllers\KompensasiEmisiController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/emissions', [AdminController::class, 'emissions'])->name('admin.emissions');
    Route::get('/credits', [AdminController::class, 'credits'])->name('admin.credits');
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('admin.notifications');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
});

// Staff/Perusahaan Routes
Route::prefix('staff')->group(function () {
    Route::get('/', [PerusahaanController::class, 'index'])->name('staff.dashboard');
    Route::resource('perusahaan', PerusahaanController::class);
});

// Faktor Emisi Routes
Route::resource('faktor-emisi', FaktorEmisiController::class);

// Emissions API Routes
Route::prefix('api')->group(function () {
    Route::post('/emissions', function (Request $request) {
        return response()->json(['success' => true]);
    });
    
    Route::delete('/emissions/{id}', function ($id) {
        return response()->json(['success' => true]);
    });
});

// Manager Routes 
Route::prefix('manager')->group(function () {
    // Dashboard route
    Route::get('/dashboard', function () {
        return view('manager.dashboard');
    })->name('manager.dashboard');

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

    Route::get('/faktor-emisi', function () {
        return view('manager.faktor-emisi.index');
    })->name('manager.faktor-emisi.index');

    Route::get('/penyedia-carbon-credit', function () {
        return view('manager.penyedia.index');
    })->name('manager.penyedia.index');

    Route::get('/carbon-credit', function () {
        return view('manager.carbon_credit.index');
    })->name('manager.carbon_credit.index');

    Route::get('/profile', function () {
        return view('manager.profile');
    })->name('manager.profile');
});
