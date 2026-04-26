<?php

/**
 * Request de validación para registro de empleados.
 * 
 * Valida datos de usuario + datos específicos de empleado.
 * Usado en el flujo de registro público o interno.
 * 
 * @package     SiempreColgados
 * @subpackage  Http\Requests
 * @author      CFGS DWES IES La Marisma
 * @date        2026-04-25
 * @version     1.0.0
 * @link        https://github.com/tuusuario/siemprecolgados-dwes
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterEmpleadoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Cualquier usuario no autenticado puede registrarse
        return !auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Datos de User (Laravel Auth)
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],

            // Datos de Empleado (específicos del proyecto)
            'dni'        => ['required', 'string', 'size:9', 'unique:empleados,dni', 'regex:/^[0-9]{8}[A-Z]$/i'],
            'telefono'   => ['nullable', 'string', 'max:20', 'regex:/^[0-9\s\-\+\(\)\.]{7,20}$/'],
            'direccion'  => ['nullable', 'string', 'max:200'],
            'fecha_alta' => ['required', 'date', 'before_or_equal:today'],
            'tipo'       => ['nullable', 'in:operario,administrador'], // Por defecto: operario
        ];
    }

    /**
     * Custom validation messages in Spanish.
     */
    public function messages(): array
    {
        return [
            'dni.regex'      => 'El DNI debe tener formato válido: 8 dígitos + letra (ej: 12345678A)',
            'telefono.regex' => 'El teléfono debe contener solo números y caracteres válidos',
            'fecha_alta.before_or_equal' => 'La fecha de alta no puede ser futura',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ];
    }
}
