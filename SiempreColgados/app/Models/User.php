<?php

/**
 * Modelo de usuario con sistema de roles y relaciones para SiempreColgados.
 * 
 * Extiende el modelo base de Laravel Auth añadiendo:
 * - Campo 'role' para diferenciar admin/operario
 * - Métodos helpers isAdmin() e isOperario()
 * - Relación uno-a-uno con Empleado
 * - Integración con Google OAuth (Socialite)
 * 
 * @package     SiempreColgados
 * @subpackage  Models
 * @author      CFGS DWES IES La Marisma
 * @date        2026-04-25
 * @version     1.2.0
 * @link        https://github.com/tuusuario/siemprecolgados-dwes
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        // Campos para Google OAuth
        'google_id',
        'avatar',
        'google_token',
        'google_refresh_token',
    ];

    /**
     * Los atributos que deben ocultarse al serializar.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_token',
        'google_refresh_token',
    ];

    /**
     * Obtener los atributos que deben ser castejados.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =========================================================================
    // RELACIONES
    // =========================================================================

    /**
     * Obtiene el empleado asociado a este usuario.
     *
     * @return HasOne<Empleado, User>
     */
    public function empleado(): HasOne
    {
        return $this->hasOne(Empleado::class);
    }

    // =========================================================================
    // MÉTODOS DE ROL (para vistas y controladores)
    // =========================================================================

    /**
     * Verifica si el usuario es administrador.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin'
            || $this->role === 'administrador'
            || (isset($this->empleado) && $this->empleado->esAdministrador());
    }

    /**
     * Verifica si el usuario es operario.
     *
     * @return bool
     */
    public function isOperario(): bool
    {
        return $this->role === 'operario'
            || (isset($this->empleado) && $this->empleado->esOperario());
    }

    /**
     * Obtiene el nombre legible del rol.
     *
     * @return string
     */
    public function getRoleNameAttribute(): string
    {
        return match ($this->role) {
            'admin', 'administrador' => 'Administrador',
            'operario' => 'Operario',
            default => ucfirst($this->role ?? 'usuario'),
        };
    }

    // =========================================================================
    // ✅ MÉTODOS PARA GOOGLE OAUTH
    // =========================================================================

    /**
     * Crear o actualizar usuario desde datos de Google.
     *
     * @param SocialiteUser $socialUser
     * @return self
     */
    public static function createOrUpdateFromGoogle(SocialiteUser $socialUser): self
    {
        $user = static::where('email', $socialUser->getEmail())
            ->orWhere('google_id', $socialUser->getId())
            ->first();

        if ($user) {
            $user->update([
                'google_id'            => $socialUser->getId(),
                'avatar'               => $socialUser->getAvatar(),
                'google_token'         => $socialUser->token,
                'google_refresh_token' => $socialUser->refreshToken ?? null,
                'name'                 => $socialUser->getName() ?? $user->name,
            ]);
        } else {
            $user = static::create([
                'name'                 => $socialUser->getName(),
                'email'                => $socialUser->getEmail(),
                'google_id'            => $socialUser->getId(),
                'avatar'               => $socialUser->getAvatar(),
                'google_token'         => $socialUser->token,
                'google_refresh_token' => $socialUser->refreshToken ?? null,
                'password'             => bcrypt(str()->random(24)),
                'role'                 => 'operario',
            ]);
        }

        return $user;
    }

    /**
     * Verifica si el usuario usa solo Google para login.
     *
     * @return bool
     */
    public function usesGoogleAuth(): bool
    {
        return !empty($this->google_id);
    }

    /**
     * Obtiene el método de autenticación como texto.
     *
     * @return string
     */
    public function getAuthMethodLabelAttribute(): string
    {
        return $this->google_id ? 'Google' : 'Contraseña';
    }

    // =========================================================================
    // ✅ MÉTODOS PARA GESTIÓN DE ADMIN
    // =========================================================================

    /**
     * Scope para filtrar usuarios por método de autenticación.
     */
    public function scopeWithAuthMethod($query, string $method)
    {
        if ($method === 'google') {
            return $query->whereNotNull('google_id');
        }
        if ($method === 'password') {
            return $query->whereNull('google_id');
        }
        return $query;
    }

    /**
     * Verifica si este usuario puede ser eliminado.
     */
    public function canBeDeleted(): bool
    {
        // No permitir eliminar el propio usuario ni el último admin
        if ($this->id === auth()->id()) {
            return false;
        }

        if ($this->isAdmin() && static::where('role', 'admin')->count() <= 1) {
            return false;
        }

        return true;
    }
}
