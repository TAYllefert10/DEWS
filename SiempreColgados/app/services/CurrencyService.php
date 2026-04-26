<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Servicio de conversión de divisas.
 * Usa la API pública Currency-API (gratuita, sin límites).
 * Obligatorio usar HttpClient según el enunciado de la práctica.
 * Los tipos de cambio se cachean 1 hora para reducir llamadas a la API.
 *
 * @author Alumno DAW
 * @version 1.0
 * @date 2024-01-01
 *
 * @see https://github.com/fawazahmed0/exchange-api
 */
class CurrencyService
{
    /** @var string URL base de la API de divisas */
    private const API_URL = 'https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies';

    /** @var int Tiempo en segundos que se cachea el tipo de cambio (1 hora) */
    private const CACHE_TTL = 3600;

    /**
     * Obtiene el tipo de cambio entre dos monedas usando HttpClient.
     * Resultado: 1 unidad de $desde = X unidades de $hasta.
     *
     * @param string $desde Código ISO moneda origen  (ej: USD, GBP)
     * @param string $hasta Código ISO moneda destino (ej: EUR)
     * @return float Tipo de cambio, 1.0 si falla
     */
    public function obtenerTipoCambio(string $desde, string $hasta): float
    {
        $desde = strtolower(trim($desde));
        $hasta = strtolower(trim($hasta));

        // Si son la misma moneda, el tipo es 1:1
        if ($desde === $hasta) {
            return 1.0;
        }

        $cacheKey = "divisa_{$desde}_{$hasta}_" . now()->format('Ymd');

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($desde, $hasta) {
            try {
                // Usar Http::get() de Laravel (HttpClient / Guzzle)
                $response = Http::timeout(10)
                    ->retry(2, 500)
                    ->get(self::API_URL . "/{$desde}.json");

                if ($response->successful()) {
                    $data = $response->json();
                    $tasa = $data[$desde][$hasta] ?? null;

                    if ($tasa !== null) {
                        return (float) $tasa;
                    }
                }

                Log::warning("CurrencyService: no se encontró tasa {$desde}→{$hasta}");
            } catch (\Exception $e) {
                Log::error("CurrencyService error: " . $e->getMessage());
            }

            return 1.0; // Fallback seguro
        });
    }

    /**
     * Convierte un importe de cualquier moneda a euros.
     *
     * @param float  $importe Importe a convertir
     * @param string $moneda  Código ISO de la moneda origen
     * @return array{importe_euros: float, tipo_cambio: float}
     */
    public function convertirAEuros(float $importe, string $moneda): array
    {
        $tipoCambio = $this->obtenerTipoCambio($moneda, 'EUR');

        return [
            'importe_euros' => round($importe * $tipoCambio, 2),
            'tipo_cambio'   => $tipoCambio,
        ];
    }
}
