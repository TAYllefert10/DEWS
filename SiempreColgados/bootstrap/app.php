<?php

/**
 * Punto de entrada para la configuración de la aplicación Laravel.
 * 
 * Este archivo configura el contenedor de servicios, middleware, 
 * excepciones y enrutamiento de la aplicación SiempreColgados.
 * 
 * @package     SiempreColgados
 * @subpackage  Bootstrap
 * @author      CFGS DWES IES La Marisma
 * @date        2026-04-26
 * @version     1.1.0
 * @link        https://github.com/tuusuario/siemprecolgados-dwes
 */

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureRoleIs;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',  // ← Asegúrate de que este archivo existe
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        /*
         * Registro de middleware personalizados con alias cortos.
         * 
         * Uso en rutas: Route::middleware('role:admin')->group(...)
         */
        $middleware->alias([
            'role' => EnsureRoleIs::class,
        ]);

        /*
         * Middleware de estado de sesión para aplicaciones web.
         * Necesario para que funcione la autenticación con sesiones.
         */
        $middleware->web(append: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);

        /*
         * Configuración de middleware para API.
         * 
         * IMPORTANTE: Para que la API use sesiones web (no tokens),
         * debemos añadir StartSession y EncryptCookies al grupo API.
         */
        $middleware->api(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Session\Middleware\StartSession::class,
        ]);

        /*
         * Habilitar estado de sesión para APIs (para usar auth con sesiones).
         */
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        /*
         * Configuración del manejador de excepciones.
         */
        $exceptions->renderable(function (Throwable $e, $request) {
            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                \Log::warning('Acceso denegado', [
                    'user' => $request->user()?->id,
                    'route' => $request->path(),
                ]);
            }
        });

        if (app()->environment('production')) {
            $exceptions->reportable(function (Throwable $e) {
                return true;
            });
        }
    })
    ->withSingletons([
        // \App\Contracts\PaymentInterface::class => \App\Services\PayPalService::class,
    ])
    ->create();
