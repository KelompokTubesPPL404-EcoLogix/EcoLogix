<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Composer for user layout
        View::composer('layouts.user', function ($view) {
            if (auth()->guard('pengguna')->check()) {
                $userKode = auth()->guard('pengguna')->user()->kode_user;

                
            }
        });

        // Add composer for admin layout
        View::composer('layouts.admin', function ($view) {
           
        });
    }
}