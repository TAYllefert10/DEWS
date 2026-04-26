@extends('layouts.app')
@section('title', 'Detalle de Usuario')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-user-circle me-2 text-primary"></i>Usuario #{{ $usuario->id }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Volver al listado
        </a>
        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i>Editar
        </a>
        @if($usuario->canBeDeleted())
        <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}"
            class="d-inline"
            onsubmit="return confirm('¿Eliminar este usuario? Esta acción no se puede deshacer.')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </form>
        @endif
    </div>
</div>

<div class="row">
    {{-- Columna principal: Datos del usuario --}}
    <div class="col-lg-8">

        {{-- Tarjeta: Información básica --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-id-card me-2"></i>Información Personal</span>
                @if($usuario->google_id)
                <span class="badge bg-success"><i class="fab fa-google me-1"></i>Autenticado con Google</span>
                @else
                <span class="badge bg-secondary"><i class="fas fa-key me-1"></i>Contraseña local</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        {{-- Avatar --}}
                        @if($usuario->avatar)
                        <img src="{{ $usuario->avatar }}" alt="{{ $usuario->name }}"
                            class="rounded-circle border border-3 shadow-sm"
                            width="120" height="120"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($usuario->name) }}&background=0d6efd&color=fff&size=120'">
                        @else
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto shadow-sm"
                            style="width:120px;height:120px;font-size:48px;font-weight:600;">
                            {{ strtoupper(substr($usuario->name, 0, 1)) }}
                        </div>
                        @endif
                        <div class="mt-2">
                            <span class="badge bg-{{ $usuario->isAdmin() ? 'danger' : 'info' }} fs-6">
                                {{ $usuario->getRoleNameAttribute() }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">Nombre completo</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $usuario->name ?? '—' }}</dd>

                            <dt class="col-sm-4 text-muted">Correo electrónico</dt>
                            <dd class="col-sm-8">
                                <a href="mailto:{{ $usuario->email }}" class="text-decoration-none">
                                    {{ $usuario->email }}
                                </a>
                            </dd>

                            <dt class="col-sm-4 text-muted">Método de acceso</dt>
                            <dd class="col-sm-8">
                                @if($usuario->google_id)
                                <span class="text-success">
                                    <i class="fab fa-google me-1"></i>Google OAuth
                                    @if($usuario->google_token)
                                    <small class="text-muted d-block">Token activo</small>
                                    @endif
                                </span>
                                @else
                                <span class="text-secondary">
                                    <i class="fas fa-key me-1"></i>Contraseña tradicional
                                </span>
                                @endif
                            </dd>

                            <dt class="col-sm-4 text-muted">Fecha de registro</dt>
                            <dd class="col-sm-8">{{ $usuario->created_at?->format('d/m/Y H:i') ?? '—' }}</dd>

                            <dt class="col-sm-4 text-muted">Última actualización</dt>
                            <dd class="col-sm-8">{{ $usuario->updated_at?->format('d/m/Y H:i') ?? '—' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tarjeta: Datos técnicos de Google (solo si aplica) --}}
        @if($usuario->google_id)
        <div class="card mb-4">
            <div class="card-header">
                <i class="fab fa-google me-2 text-danger"></i>Datos de Google OAuth
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Google ID</dt>
                    <dd class="col-sm-8">
                        <code class="small">{{ $usuario->google_id }}</code>
                        <button class="btn btn-sm btn-outline-secondary ms-2"
                            onclick="navigator.clipboard.writeText('{{ $usuario->google_id }}')"
                            title="Copiar al portapapeles">
                            <i class="fas fa-copy"></i>
                        </button>
                    </dd>

                    <dt class="col-sm-4 text-muted">Avatar URL</dt>
                    <dd class="col-sm-8">
                        @if($usuario->avatar)
                        <a href="{{ $usuario->avatar }}" target="_blank" class="text-decoration-none small">
                            {{ Str::limit($usuario->avatar, 50) }}
                            <i class="fas fa-external-link-alt ms-1"></i>
                        </a>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </dd>

                    <dt class="col-sm-4 text-muted">Token de acceso</dt>
                    <dd class="col-sm-8">
                        @if($usuario->google_token)
                        <code class="small text-muted">{{ Str::limit($usuario->google_token, 30) }}...</code>
                        <span class="badge bg-success ms-2">Activo</span>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </dd>

                    <dt class="col-sm-4 text-muted">Refresh Token</dt>
                    <dd class="col-sm-8">
                        {{ $usuario->google_refresh_token ? '✅ Disponible' : '❌ No disponible' }}
                    </dd>
                </dl>
            </div>
        </div>
        @endif

        {{-- Tarjeta: Historial de actividad (opcional - se puede ampliar) --}}
        <div class="card">
            <div class="card-header">
                <i class="fas fa-history me-2"></i>Actividad Reciente
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-user-plus text-success me-2"></i>
                            <strong>Cuenta creada</strong>
                        </div>
                        <small class="text-muted">{{ $usuario->created_at?->diffForHumans() ?? '—' }}</small>
                    </div>

                    @if($usuario->google_id)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fab fa-google text-danger me-2"></i>
                            <strong>Último login con Google</strong>
                        </div>
                        <small class="text-muted">
                            {{ $usuario->updated_at?->diffForHumans() ?? '—' }}
                        </small>
                    </div>
                    @endif

                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-sync-alt text-primary me-2"></i>
                            <strong>Última actualización</strong>
                        </div>
                        <small class="text-muted">{{ $usuario->updated_at?->diffForHumans() ?? '—' }}</small>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Columna lateral: Empleado vinculado y acciones --}}
    <div class="col-lg-4">

        {{-- Tarjeta: Empleado vinculado --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-user-tie me-2"></i>Empleado Vinculado
            </div>
            <div class="card-body text-center">
                @if($usuario->empleado)
                <div class="mb-3">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width:80px;height:80px;font-size:32px;color:#0d6efd;">
                        <i class="fas fa-user"></i>
                    </div>
                    <h5 class="mb-1">{{ $usuario->empleado->nombre }}</h5>
                    <p class="text-muted small mb-2">{{ $usuario->empleado->dni }}</p>
                    <span class="badge bg-{{ $usuario->empleado->activo ? 'success' : 'secondary' }}">
                        {{ $usuario->empleado->activo ? '✅ Activo' : '❌ Inactivo' }}
                    </span>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('empleados.show', $usuario->empleado) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>Ver Empleado
                    </a>
                    <a href="{{ route('empleados.edit', $usuario->empleado) }}" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-edit me-1"></i>Editar Empleado
                    </a>
                </div>
                @else
                <div class="text-muted py-4">
                    <i class="fas fa-unlink fa-3x d-block mb-3 opacity-25"></i>
                    <p class="small">Este usuario no está vinculado a ningún empleado.</p>
                    <p class="small text-muted">Vincúalo para que aparezca en la asignación de incidencias.</p>
                </div>

                <form method="POST" action="{{ route('usuarios.vincular', $usuario) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small">Seleccionar Empleado</label>
                        <select name="empleado_id" class="form-select form-select-sm" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach(\App\Models\Empleado::whereDoesntHave('user')->orWhere('user_id', $usuario->id)->get() as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->nombre }} ({{ $emp->dni }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-link me-1"></i>Vincular Ahora
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- Tarjeta: Acciones rápidas --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-bolt me-2"></i>Acciones Rápidas
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-outline-warning">
                        <i class="fas fa-edit me-2"></i>Editar Usuario
                    </a>

                    @if(!$usuario->google_id)
                    <button class="btn btn-outline-secondary" disabled title="Solo para usuarios con contraseña local">
                        <i class="fas fa-key me-2"></i>Restablecer contraseña
                    </button>
                    @endif

                    @if($usuario->canBeDeleted())
                    <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}"
                        onsubmit="return confirm('⚠️ ¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-2"></i>Eliminar Usuario
                        </button>
                    </form>
                    @else
                    <button class="btn btn-outline-danger w-100" disabled title="No se puede eliminar este usuario">
                        <i class="fas fa-lock me-2"></i>Usuario protegido
                    </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tarjeta: Notas del admin (opcional) --}}
        <div class="card">
            <div class="card-header">
                <i class="fas fa-sticky-note me-2"></i>Notas Internas
            </div>
            <div class="card-body">
                <textarea class="form-control form-control-sm" rows="4"
                    placeholder="Añadir nota privada sobre este usuario..."></textarea>
                <small class="text-muted d-block mt-2">💡 Estas notas solo son visibles para administradores.</small>
                <button class="btn btn-sm btn-outline-primary mt-2 w-100">
                    <i class="fas fa-save me-1"></i>Guardar Nota
                </button>
            </div>
        </div>

    </div>
</div>

{{-- Modal de confirmación para acciones críticas (opcional) --}}
@if(! $usuario->canBeDeleted())
<div class="modal fade" id="modalProtegido" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Usuario Protegido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>No se puede eliminar este usuario porque:</p>
                @if($usuario->id === auth()->id())
                <ul>
                    <li>Es tu propia cuenta de administrador.</li>
                </ul>
                @elseif($usuario->isAdmin() && \App\Models\User::where('role', 'admin')->count() <= 1)
                    <ul>
                    <li>Es el único administrador restante en el sistema.</li>
                    </ul>
                    @endif
                    <p class="mb-0 small text-muted">Primero crea otro administrador o usa una cuenta diferente.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Entendido</button>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    // Copiar Google ID al portapapeles
    document.querySelectorAll('[onclick*="clipboard"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            this.innerHTML = '<i class="fas fa-check"></i> Copiado';
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-copy"></i>';
            }, 2000);
        });
    });
</script>
@endpush
@endsection