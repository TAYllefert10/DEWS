<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Proveedor de autorización - Define los Gates de acceso por rol
 *
 * @author Alumno DAW
 * @version 1.0
 * @date 2024-01-01
 */
class AuthServiceProvider extends ServiceProvider
{
    /** @var array<class-string,class-string> Políticas registradas */
    protected $policies = [];

    /**
     * Registra los Gates de autorización.
     * Uso en Blade:   @can('admin') ... @endcan
     * Uso en rutas:   ->middleware('can:admin')
     * Uso en PHP:     Gate::allows('admin')
     *
     * @return void
     */
    public function boot(): void
    {
        /**
         * Gate 'admin': solo empleados de tipo administrador.
         *
         * @param \App\Models\User $user
         * @return bool
         */
        Gate::define('admin', function (\App\Models\User $user): bool {
            return $user->isAdmin();  // ← Usa el método que YA sabes que funciona
        });

        /**
         * Gate 'operario': solo empleados de tipo operario.
         *
         * @param \App\Models\User $user
         * @return bool
         */
        Gate::define('operario', function (\App\Models\User $user): bool {
            return $user->empleado?->esOperario() ?? false;
        });
    }
}
