<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;

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
        // ✅ Paginación Bootstrap 5
        Paginator::useBootstrapFive();

        // ✅ Gates de autorización (definidos aquí para evitar problemas de caché)
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('operario', function ($user) {
            return $user->isOperario();
        });
    }
}
