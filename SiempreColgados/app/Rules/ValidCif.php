<?php

/**
 * Regla de validación personalizada para CIF español.
 * 
 * Implementa la interfaz ValidationRule de Laravel 12.
 * Formato válido: Letra+7 dígitos+Letra o 8 dígitos+Letra (ej: B12345678).
 * 
 * @package     SiempreColgados
 * @subpackage  Rules
 * @author      CFGS DWES IES La Marisma
 * @date        2026-04-25
 * @version     1.0.0
 * @link        https://github.com/tuusuario/siemprecolgados-dwes
 */

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCif implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Formato básico CIF español: [A-Z0-9][0-9]{7}[A-Z0-9]
        if (!preg_match('/^[A-Z0-9][0-9]{7}[A-Z0-9]$/', strtoupper($value))) {
            $fail('El CIF debe tener formato válido español (ej: B12345678).');
        }
    }
}
