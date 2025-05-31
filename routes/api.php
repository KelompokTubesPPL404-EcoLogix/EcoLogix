<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmisiKarbonController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotifikasiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('emisi-carbon', EmisiKarbonController::class);

// Dashboard API Routes
Route::middleware('auth:web')->group(function () {
    Route::get('/dashboard/emisi-chart', [DashboardController::class, 'getEmisiChart']);
    Route::get('/dashboard/emisi-by-category', [DashboardController::class, 'getEmisiByCategory']);
    Route::get('/dashboard/stats', [DashboardController::class, 'getDashboardStats']);
    
    // Route untuk notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'getNotifikasi']);
});