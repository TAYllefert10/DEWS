<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
    /**
     * Redirige a Google para autenticación OAuth2
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['profile', 'email'])
            ->redirect();
    }

    /**
     * Callback tras autenticación exitosa en Google
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            if ($request->has('error')) {
                $errorMsg = $request->get('error_description', $request->get('error'));
                Log::error('Google OAuth Error: ' . $errorMsg);
                return redirect()->route('login')->with('error', 'Google: ' . $errorMsg);
            }

            $socialUser = Socialite::driver('google')->user();

            if (!$socialUser->getEmail()) {
                return redirect()->route('login')->with('error', 'No se pudo obtener tu email de Google.');
            }

            // ✅ Crea/actualiza en tabla empleados con role='operario'
            $empleado = Empleado::createOrUpdateFromGoogle($socialUser);

            // ✅ Inicia sesión con el modelo Empleado
            Auth::login($empleado, true);

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            Log::error('Google Auth Callback Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile() . ':' . $e->getLine(),
            ]);

            return redirect()->route('login')->with(
                'error',
                app()->environment('local') ? $e->getMessage() : 'Error al iniciar sesión con Google.'
            );
        }
    }
}
