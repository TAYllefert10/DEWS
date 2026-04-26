<?php

/**
 * Rutas API para frontend Vue.js (Problema 3.2 - SPA con Vue+CDN)
 * @package SiempreColgados
 * @author CFGS DWES IES La Marisma
 * @date 2026-04-26
 * @version 1.0
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IncidenciaApiController;

/*
|--------------------------------------------------------------------------
| API Routes - Para Vue SPA (mismo dominio, sesiones web)
|--------------------------------------------------------------------------
|
| Estas rutas son consumidas por el frontend Vue.js en /incidencias-vue.
| Usan middleware 'auth' (sesiones web) en lugar de 'auth:api' (tokens).
|
*/

Route::middleware('auth')->group(function () {

    // ✅ CRUD completo de incidencias para Vue SPA
    // GET    /api/incidencias ................. index (listar con filtros)
    // POST   /api/incidencias ................. store (crear)
    // GET    /api/incidencias/{incidencia} .... show (detalle)
    // PUT    /api/incidencias/{incidencia} .... update (actualizar)
    // DELETE /api/incidencias/{incidencia} .... destroy (eliminar)
    Route::apiResource('incidencias', IncidenciaApiController::class);

    // ✅ Listados para selects del formulario Vue
    Route::get('/operarios', [IncidenciaApiController::class, 'operarios'])->name('api.operarios');
    Route::get('/clientes', [IncidenciaApiController::class, 'clientes'])->name('api.clientes');
});
