<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Incidencia - Registro de averías/mantenimientos
 * 
 * @author CFGS DWES IES La Marisma
 * @version 2.1
 * @date 2026-04-26
 * 
 * @property int $id
 * @property int $cliente_id
 * @property int|null $operario_id
 * @property string $persona_contacto
 * @property string|null $telefono_contacto
 * @property string $descripcion
 * @property string|null $correo
 * @property string $estado  P=Pendiente, E=En proceso, R=Realizada, C=Cancelada
 * @property string|null $fecha_realizacion
 * @property string|null $fichero  Ruta del archivo adjunto
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read string $nombre_estado  Nombre legible del estado
 * @property-read string $color_estado  Color de Bootstrap para el badge
 */
class Incidencia extends Model
{
    use HasFactory;

    protected $table = 'incidencias';

    protected $fillable = [
        'cliente_id',
        'operario_id',
        'persona_contacto',
        'telefono_contacto',
        'descripcion',
        'correo',
        'estado',
        'fecha_realizacion',
        'fichero',
    ];

    protected $casts = [
        'fecha_realizacion' => 'date',
    ];

    // =========================================================================
    // RELACIONES
    // =========================================================================

    /**
     * Cliente que reporta la incidencia.
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Operario asignado para resolver la incidencia.
     */
    public function operario(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'operario_id');
    }

    // =========================================================================
    // ✅ ACCESSORS - Devuelven VALORES, NO relaciones (patrón get*Attribute)
    // =========================================================================

    /**
     * Obtiene el nombre legible del estado.
     * 
     * Uso en vista: {{ $incidencia->nombre_estado }}
     * 
     * @return string
     */
    public function getNombreEstadoAttribute(): string
    {
        return match ($this->estado) {
            'P' => 'Pendiente',
            'E' => 'En proceso',
            'R' => 'Realizada',
            'C' => 'Cancelada',
            default => $this->estado ?? 'Desconocido',
        };
    }

    /**
     * Obtiene el color de Bootstrap para el badge del estado.
     * 
     * Uso en vista: bg-{{ $incidencia->color_estado }}
     * 
     * @return string
     */
    public function getColorEstadoAttribute(): string
    {
        return match ($this->estado) {
            'P' => 'warning',    // 🟡 Amarillo
            'E' => 'info',       // 🔵 Azul
            'R' => 'success',    // 🟢 Verde
            'C' => 'danger',     // 🔴 Rojo
            default => 'secondary', // ⚪ Gris
        };
    }

    // =========================================================================
    // SCOPES - Filtros reutilizables para consultas
    // =========================================================================

    /**
     * Filtra incidencias asignadas a un operario específico.
     */
    public function scopeDeOperario($query, $operarioId)
    {
        return $query->where('operario_id', $operarioId);
    }

    /**
     * Filtra incidencias pendientes.
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'P');
    }

    /**
     * Filtra incidencias realizadas.
     */
    public function scopeRealizadas($query)
    {
        return $query->where('estado', 'R');
    }

    /**
     * Filtra incidencias activas (pendientes o en proceso).
     */
    public function scopeActivas($query)
    {
        return $query->whereIn('estado', ['P', 'E']);
    }

    // =========================================================================
    // MÉTODOS DE INSTANCIA - Lógica de negocio
    // =========================================================================

    /**
     * @deprecated Usar $incidencia->nombre_estado (accessor)
     */
    public function nombre_estado(): string
    {
        return $this->nombre_estado;
    }

    /**
     * @deprecated Usar $incidencia->color_estado (accessor)
     */
    public function color_estado(): string
    {
        return $this->color_estado;
    }

    /**
     * Verifica si la incidencia tiene un archivo adjunto.
     */
    public function tieneFichero(): bool
    {
        return !empty($this->fichero);
    }

    /**
     * Obtiene la URL para descargar el archivo adjunto.
     */
    public function urlFichero(): ?string
    {
        return $this->tieneFichero()
            ? route('incidencias.fichero', $this)
            : null;
    }

    /**
     * Obtiene el nombre original del archivo adjunto, eliminando el prefijo técnico.
     * 
     * Ejemplo: 
     *   Almacenado: "incidencias/1714123456_abc123_mi-documento.pdf"
     *   Retorna: "mi-documento.pdf"
     * 
     * @return string|null
     */
    public function nombreArchivoVisible(): ?string
    {
        if (!$this->fichero) {
            return null;
        }

        // Obtener solo el nombre del archivo (sin ruta)
        $basename = basename($this->fichero);

        // Patrón esperado: timestamp_uuid_nombre-original.ext
        if (preg_match('/^\d+_[a-z0-9]+_(.+)$/', $basename, $matches)) {
            return $matches[1];
        }

        // Si no coincide el patrón, devolver el nombre tal cual
        return $basename;
    }

    // =========================================================================
    // MÉTODOS DE ESTADO - Helpers para lógica de negocio
    // =========================================================================

    /**
     * Verifica si la incidencia está pendiente.
     */
    public function estaPendiente(): bool
    {
        return $this->estado === 'P';
    }

    /**
     * Verifica si la incidencia está en proceso.
     */
    public function estaEnProceso(): bool
    {
        return $this->estado === 'E';
    }

    /**
     * Verifica si la incidencia está realizada.
     */
    public function estaRealizada(): bool
    {
        return $this->estado === 'R';
    }

    /**
     * Verifica si la incidencia está cancelada.
     */
    public function estaCancelada(): bool
    {
        return $this->estado === 'C';
    }
}
