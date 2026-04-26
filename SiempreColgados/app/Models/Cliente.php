<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'cif',
        'nombre',
        'telefono',
        'correo',
        'cuenta_corriente',
        'pais',
        'moneda',
        'importe_cuota',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'importe_cuota' => 'decimal:2',
    ];

    // Scope para clientes activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
