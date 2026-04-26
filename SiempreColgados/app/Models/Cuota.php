<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Cuota - Cuotas de mantenimiento de clientes
 *
 * @author Alumno DAW
 * @version 1.0
 * @date 2024-01-01
 *
 * @property int    $id
 * @property int    $cliente_id      FK cliente
 * @property string $concepto        Descripción de la cuota
 * @property \Carbon\Carbon $fecha_emision Fecha de emisión
 * @property float  $importe         Importe en moneda local
 * @property string $moneda          Código ISO-3 moneda
 * @property bool   $pagada          Si está pagada
 * @property \Carbon\Carbon|null $fecha_pago Fecha de pago efectivo
 * @property float|null $importe_euros Importe convertido a EUR
 * @property float|null $tipo_cambio   Tipo de cambio aplicado
 * @property string|null $notas       Notas adicionales
 */
class Cuota extends Model
{
    use HasFactory;

    /** @var string Tabla asociada */
    protected $table = 'cuotas';

    /** @var array<int,string> Campos asignables */
    protected $fillable = [
        'cliente_id',
        'concepto',
        'fecha_emision',
        'importe',
        'moneda',
        'pagada',
        'fecha_pago',
        'importe_euros',
        'tipo_cambio',
        'notas',
    ];

    /** @var array<string,string> Conversión de tipos */
    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_pago'    => 'date',
        'pagada'        => 'boolean',
        'importe'       => 'decimal:2',
        'importe_euros' => 'decimal:2',
        'tipo_cambio'   => 'decimal:6',
    ];

    // =========================================================================
    // RELACIONES
    // =========================================================================

    /**
     * Cliente al que pertenece la cuota.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cliente(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    /**
     * Filtra cuotas pendientes de pago.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendientes(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('pagada', false);
    }

    /**
     * Filtra cuotas pagadas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePagadas(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('pagada', true);
    }

    // =========================================================================
    // MÉTODOS DE NEGOCIO
    // =========================================================================

    /**
     * Marca la cuota como pagada registrando el tipo de cambio del día.
     *
     * @param float $importeEuros Importe en euros
     * @param float $tipoCambio   Tipo de cambio aplicado
     * @return void
     */
    public function marcarComoPagada(float $importeEuros, float $tipoCambio): void
    {
        $this->update([
            'pagada'        => true,
            'fecha_pago'    => now()->toDateString(),
            'importe_euros' => $importeEuros,
            'tipo_cambio'   => $tipoCambio,
        ]);
    }

    /**
     * Genera una remesa mensual para todos los clientes activos.
     * Crea una cuota por cada cliente usando su importe base.
     *
     * @param string $concepto Concepto de la remesa
     * @return int Número de cuotas creadas
     */
    public static function generarRemesaMensual(string $concepto = 'Cuota mensual de mantenimiento'): int
    {
        $clientes = Cliente::activos()->get();
        $count    = 0;
        $mesAnio  = now()->locale('es')->isoFormat('MMMM YYYY');

        foreach ($clientes as $cliente) {
            static::create([
                'cliente_id'    => $cliente->id,
                'concepto'      => $concepto . ' - ' . ucfirst($mesAnio),
                'fecha_emision' => now()->toDateString(),
                'importe'       => $cliente->importe_cuota,
                'moneda'        => $cliente->moneda,
                'pagada'        => false,
            ]);
            $count++;
        }

        return $count;
    }
}
