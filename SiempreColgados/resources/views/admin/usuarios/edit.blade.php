@extends('layouts.app')
@section('title', 'Editar Usuario')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-user-edit me-2 text-warning"></i>Editar Usuario #{{ $usuario->id }}</h2>
    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i>Datos del Usuario</div>
            <div class="card-body">
                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
                    @csrf @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $usuario->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $usuario->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Rol *</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="operario" {{ old('role', $usuario->role)==='operario'?'selected':'' }}>🔧 Operario</option>
                                <option value="admin" {{ old('role', $usuario->role)==='admin'?'selected':'' }}>👑 Administrador</option>
                            </select>
                            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Método de Acceso</label>
                            <div class="form-control-plaintext">
                                @if($usuario->google_id)
                                <span class="badge bg-success"><i class="fab fa-google me-1"></i>Google</span>
                                <small class="text-muted d-block">No puede cambiar contraseña (autenticación externa)</small>
                                @else
                                <span class="badge bg-secondary"><i class="fas fa-key me-1"></i>Contraseña</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-warning">Actualizar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Vincular con Empleado --}}
        <div class="card">
            <div class="card-header"><i class="fas fa-link me-2"></i>Vincular con Empleado</div>
            <div class="card-body">
                @if($usuario->empleado)
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Vinculado a:</strong><br>
                    {{ $usuario->empleado->nombre }}<br>
                    <small class="text-muted">DNI: {{ $usuario->empleado->dni }}</small>
                </div>
                <a href="{{ route('empleados.edit', $usuario->empleado) }}" class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-edit me-1"></i>Editar Empleado
                </a>
                @else
                <p class="text-muted small mb-3">Vincula este usuario con un registro de empleado para que aparezca en la asignación de incidencias.</p>

                <form method="POST" action="{{ route('usuarios.vincular', $usuario) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small">Seleccionar Empleado</label>
                        <select name="empleado_id" class="form-select form-select-sm" required>
                            <option value="">-- Seleccionar --</option>
                            @foreach($empleados as $emp)
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

        {{-- Info adicional --}}
        <div class="card mt-3">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i>Información</div>
            <div class="card-body small">
                <dl class="row mb-0">
                    <dt class="col-6">ID Google:</dt>
                    <dd class="col-6">{{ $usuario->google_id ?? '—' }}</dd>

                    <dt class="col-6">Registrado:</dt>
                    <dd class="col-6">{{ $usuario->created_at->format('d/m/Y') }}</dd>

                    <dt class="col-6">Último acceso:</dt>
                    <dd class="col-6">{{ $usuario->last_login ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection