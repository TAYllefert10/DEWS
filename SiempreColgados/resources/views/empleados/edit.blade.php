@extends('layouts.app')
@section('title', 'Editar Empleado')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-edit me-2 text-warning"></i>Editar Empleado</h2>
    <a href="{{ route('empleados.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-info-circle me-2"></i>{{ $empleado->nombre }}</span>
        @if($empleado->google_id)
        <span class="badge bg-success"><i class="fab fa-google me-1"></i>Google OAuth</span>
        @endif
    </div>
    <div class="card-body">
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('empleados.update', $empleado) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                {{-- DNI (readonly) --}}
                <div class="col-md-4">
                    <label class="form-label">DNI</label>
                    <input type="text" class="form-control-plaintext fw-bold" value="{{ $empleado->dni }}" readonly>
                    <small class="text-muted">El DNI no se puede modificar</small>
                </div>

                {{-- Nombre --}}
                <div class="col-md-8">
                    <label class="form-label">Nombre Completo *</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                        name="nombre" value="{{ old('nombre', $empleado->nombre) }}" required>
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Email (readonly si usa Google) --}}
                <div class="col-md-6">
                    <label class="form-label">Correo Electrónico *</label>
                    @if($empleado->google_id)
                    <input type="email" class="form-control-plaintext" value="{{ $empleado->email }}" readonly>
                    <small class="text-muted">El email no se puede modificar para usuarios de Google</small>
                    @else
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email', $empleado->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @endif
                </div>

                {{-- Teléfono --}}
                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                        name="telefono" value="{{ old('telefono', $empleado->telefono) }}"
                        placeholder="+34 612 345 678">
                    @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Dirección --}}
                <div class="col-12">
                    <label class="form-label">Dirección</label>
                    <input type="text" class="form-control @error('direccion') is-invalid @enderror"
                        name="direccion" value="{{ old('direccion', $empleado->direccion) }}"
                        placeholder="C/ Ejemplo 123, Huelva">
                    @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Fecha de Alta --}}
                <div class="col-md-4">
                    <label class="form-label">Fecha de Alta *</label>
                    <input type="date" class="form-control @error('fecha_alta') is-invalid @enderror"
                        name="fecha_alta" value="{{ old('fecha_alta', $empleado->fecha_alta?->format('Y-m-d')) }}"
                        max="{{ date('Y-m-d') }}" required>
                    @error('fecha_alta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Tipo de Empleado --}}
                <div class="col-md-4">
                    <label class="form-label">Tipo de Empleado *</label>
                    <select class="form-select @error('tipo') is-invalid @enderror" name="tipo" required>
                        <option value="">Seleccionar...</option>
                        <option value="operario" {{ old('tipo', $empleado->tipo)==='operario'?'selected':'' }}>
                            🔧 Operario
                        </option>
                        <option value="administrador" {{ old('tipo', $empleado->tipo)==='administrador'?'selected':'' }}>
                            🛡️ Administrador
                        </option>
                    </select>
                    @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Estado (Activo/Inactivo) --}}
                <div class="col-md-4">
                    <label class="form-label">Estado *</label>
                    <select class="form-select @error('activo') is-invalid @enderror" name="activo" required>
                        <option value="1" {{ old('activo', $empleado->activo)?'selected':'' }}>✅ Activo</option>
                        <option value="0" {{ !old('activo', $empleado->activo)?'selected':'' }}>❌ Inactivo</option>
                    </select>
                    @error('activo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Contraseña (solo si NO usa Google) --}}
                @if(!$empleado->google_id)
                <div class="col-md-6">
                    <label class="form-label">Nueva Contraseña</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" placeholder="Dejar vacío para mantener la actual">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Mínimo 8 caracteres. Solo rellenar si desea cambiarla.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirmar Contraseña</label>
                    <input type="password" class="form-control" name="password_confirmation"
                        placeholder="Repetir nueva contraseña">
                </div>
                @else
                <div class="col-12">
                    <div class="alert alert-info mb-0">
                        <i class="fab fa-google me-2"></i>
                        <strong>Este empleado usa Google para iniciar sesión.</strong>
                        <br><small class="text-muted">No tiene contraseña local. Para cambiar el método de acceso, contacte con administración.</small>
                    </div>
                </div>
                @endif
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('empleados.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save me-1"></i>Actualizar Empleado
                </button>
            </div>
        </form>
    </div>
</div>
@endsection