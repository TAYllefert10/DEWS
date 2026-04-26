<?php

/**
 * Rutas WEB de SiempreColgados - Versión final SIN Vue
 * @package SiempreColgados
 * @author CFGS DWES IES La Marisma
 * @date 2026-04-26
 * @version 2.6.0
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\IncidenciaClienteController;
use App\Http\Controllers\Auth\GoogleAuthController;

/* =============================================================================
   RUTAS PÚBLICAS
   ============================================================================= */

Route::get('/', fn() => redirect()->route('login'));

// Formulario público para que clientes registren incidencias
Route::get('/incidencia-cliente', [IncidenciaClienteController::class, 'showForm'])
    ->name('incidencia.cliente.form');
Route::post('/incidencia-cliente', [IncidenciaClienteController::class, 'store'])
    ->name('incidencia.cliente.store');

// Rutas de autenticación por defecto de Laravel
require __DIR__ . '/auth.php';

// ✅ Rutas para Google OAuth (públicas)
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/google', [GoogleAuthController::class, 'redirectToGoogle'])
        ->name('google');
    Route::get('/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])
        ->name('google.callback');
});

/* =============================================================================
   RUTAS PROTEGIDAS (requieren autenticación)
   ============================================================================= */
Route::middleware('auth')->group(function () {

    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD de Incidencias (para todos los usuarios autenticados)
    Route::resource('incidencias', IncidenciaController::class)->names([
        'index' => 'incidencias.index',
        'create' => 'incidencias.create',
        'store' => 'incidencias.store',
        'show' => 'incidencias.show',
        'edit' => 'incidencias.edit',
        'update' => 'incidencias.update',
        'destroy' => 'incidencias.destroy',
    ]);

    // Descargar archivo adjunto de incidencia
    Route::get('/incidencias/{incidencia}/fichero', [IncidenciaController::class, 'descargarFichero'])
        ->name('incidencias.fichero');

    /* -------------------------------------------------------------------------
       ✅ ADMINISTRACIÓN (SOLO ADMIN - middleware 'can:admin')
       ------------------------------------------------------------------------- */
    Route::middleware('can:admin')->group(function () {

        // CRUD de Empleados (gestión de usuarios del sistema)
        Route::resource('empleados', EmpleadoController::class)->names([
            'index' => 'empleados.index',
            'create' => 'empleados.create',
            'store' => 'empleados.store',
            'show' => 'empleados.show',
            'edit' => 'empleados.edit',
            'update' => 'empleados.update',
            'destroy' => 'empleados.destroy',
        ]);
        // Dar de alta a empleado (reactivar)
        Route::patch('/empleados/{empleado}/alta', [EmpleadoController::class, 'darAlta'])
            ->name('empleados.alta');

        // CRUD de Clientes
        Route::resource('clientes', ClienteController::class)->names([
            'index' => 'clientes.index',
            'create' => 'clientes.create',
            'store' => 'clientes.store',
            'show' => 'clientes.show',
            'edit' => 'clientes.edit',
            'update' => 'clientes.update',
            'destroy' => 'clientes.destroy',
        ]);
        // Dar de alta a cliente (activar)
        Route::patch('/clientes/{cliente}/alta', [ClienteController::class, 'darAlta'])
            ->name('clientes.alta');

        // CRUD de Cuotas
        Route::resource('cuotas', CuotaController::class)->names([
            'index' => 'cuotas.index',
            'create' => 'cuotas.create',
            'store' => 'cuotas.store',
            'show' => 'cuotas.show',
            'edit' => 'cuotas.edit',
            'update' => 'cuotas.update',
            'destroy' => 'cuotas.destroy',
        ]);

        // Acciones adicionales de cuotas
        Route::post('/cuotas/remesa', [CuotaController::class, 'generarRemesa'])
            ->name('cuotas.remesa');
        Route::post('/cuotas/{cuota}/pagar', [CuotaController::class, 'marcarPagada'])
            ->name('cuotas.pagar');
        Route::get('/cuotas/{cuota}/factura', [CuotaController::class, 'verFactura'])
            ->name('cuotas.factura');
        Route::get('/cuotas/{cuota}/factura/descargar', [CuotaController::class, 'descargarFactura'])
            ->name('cuotas.factura.descargar');
        Route::post('/cuotas/{cuota}/factura/enviar', [CuotaController::class, 'enviarFactura'])
            ->name('cuotas.factura.enviar');
    });
});
