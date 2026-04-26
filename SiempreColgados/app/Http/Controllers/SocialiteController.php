<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empleado;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Controlador para autenticación OAuth con Google (y otros proveedores).
 * Usa el paquete Laravel Socialite.
 * Requiere: composer require laravel/socialite
 *
 * @author Alumno DAW
 * @version 1.0
 * @date 2024-01-01
 *
 * @see https://laravel.com/docs/socialite
 */
class SocialiteController extends Controller
{
    /**
     * Redirige al usuario a la página de autenticación de Google.
     * Ruta: GET /auth/google
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToGoogle(): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Procesa el callback de Google tras la autenticación.
     * Ruta: GET /auth/google/callback
     *
     * Si el email del usuario de Google coincide con un empleado registrado,
     * lo autentica. Si no existe, lo crea como usuario básico.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback(): \Illuminate\Http\RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Error al conectar con Google. Inténtalo de nuevo.']);
        }

        // Buscar usuario existente por email
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Usuario ya registrado → autenticar directamente
            Auth::login($user, true);

            return redirect()->route('dashboard')
                ->with('success', 'Bienvenido, ' . $user->name . '. Has accedido con Google.');
        }

        // Usuario nuevo → crear cuenta
        // NOTA: Los usuarios nuevos via Google no tendrán rol de empleado asignado
        // Un administrador deberá asignarles el empleado manualmente.
        $newUser = User::create([
            'name'              => $googleUser->getName(),
            'email'             => $googleUser->getEmail(),
            'password'          => bcrypt(Str::random(32)), // contraseña aleatoria
            'email_verified_at' => now(),
        ]);

        Auth::login($newUser, true);

        return redirect()->route('dashboard')
            ->with('success', 'Cuenta creada con Google. Un administrador te asignará los permisos.');
    }
}
