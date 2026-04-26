<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Socialite\Contracts\User as SocialiteUser;

/**
 * Modelo Empleado - Tabla de autenticación principal para SiempreColgados
 * 
 * @author CFGS DWES IES La Marisma
 * @version 2.0
 * @date 2026-04-26
 * 
 * @property int    $id
 * @property string $dni
 * @property string $nombre
 * @property string $email
 * @property string|null $password
 * @property string|null $google_id
 * @property string|null $avatar
 * @property string $tipo  'operario'|'administrador'
 * @property bool   $activo
 */
class Empleado extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'empleados';

    /** @var array<int,string> Campos asignables en masa */
    protected $fillable = [
        'dni',
        'nombre',
        'email',
        'password',
        'telefono',
        'direccion',
        'fecha_alta',
        'tipo',
        'activo',
        'google_id',
        'avatar',
        'google_token',
        'google_refresh_token',
    ];

    /** @var array<int,string> Campos ocultos en serialización */
    protected $hidden = [
        'password',
        'remember_token',
        'google_token',
        'google_refresh_token',
    ];

    /** @var array<string,string> Conversión de tipos */
    protected function casts(): array
    {
        return [
            'fecha_alta' => 'date',
            'password'   => 'hashed',
            'activo'     => 'boolean',
        ];
    }

    // =========================================================================
    // MÉTODOS DE ROL (según PDF)
    // =========================================================================
    public function esAdministrador(): bool
    {
        return $this->tipo === 'administrador';
    }

    public function esOperario(): bool
    {
        return $this->tipo === 'operario';
    }

    // Alias para compatibilidad con middlewares/gates existentes
    public function isAdmin(): bool
    {
        return $this->esAdministrador();
    }
    public function isOperario(): bool
    {
        return $this->esOperario();
    }

    // =========================================================================
    // ✅ GOOGLE OAUTH (Auto-asigna 'operario' por defecto)
    // =========================================================================
    public static function createOrUpdateFromGoogle(SocialiteUser $socialUser): self
    {
        $empleado = static::where('email', $socialUser->getEmail())
            ->orWhere('google_id', $socialUser->getId())
            ->first();

        if ($empleado) {
            $empleado->update([
                'google_id'            => $socialUser->getId(),
                'avatar'               => $socialUser->getAvatar(),
                'google_token'         => $socialUser->token,
                'google_refresh_token' => $socialUser->refreshToken ?? null,
                'nombre'               => $socialUser->getName() ?? $empleado->nombre,
            ]);
        } else {
            // ✅ Generar DNI válido de 9 caracteres para usuarios Google
            // Opción A: Hash del email (único y siempre 9 chars)
            $dniPlaceholder = strtoupper(substr(md5($socialUser->getEmail()), 0, 9));

            // Opción B (alternativa): 'G' + 8 dígitos del ID de Google
            // $dniPlaceholder = 'G' . substr(str_pad($socialUser->getId(), 8, '0', STR_PAD_LEFT), -8);

            $empleado = static::create([
                'dni'                    => $dniPlaceholder,  // ✅ 9 caracteres exactos
                'nombre'                 => $socialUser->getName(),
                'email'                => $socialUser->getEmail(),
                'password'             => bcrypt(str()->random(24)),
                'fecha_alta'           => now(),
                'tipo'                 => 'operario',
                'activo'               => true,
                'google_id'            => $socialUser->getId(),
                'avatar'               => $socialUser->getAvatar(),
                'google_token'         => $socialUser->token,
                'google_refresh_token' => $socialUser->refreshToken ?? null,
            ]);
        }

        return $empleado;
    }

    // =========================================================================
    // RELACIONES
    // =========================================================================
    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'operario_id');
    }

    // =========================================================================
    // SCOPES
    // =========================================================================
    public function scopeOperarios($query)
    {
        return $query->where('tipo', 'operario')->where('activo', true);
    }

    public function scopeAdministradores($query)
    {
        return $query->where('tipo', 'administrador')->where('activo', true);
    }
}
