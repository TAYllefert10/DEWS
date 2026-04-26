@extends('layouts.app')
@section('title', 'Nuevo Empleado')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-user-plus me-2 text-primary"></i>Nuevo Empleado</h2>
    <a href="{{ route('empleados.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-info-circle me-2"></i>Datos del Empleado</div>
    <div class="card-body">

        {{-- ❌ ELIMINADO: Bloque global de errores que causaba la duplicación --}}

        <form method="POST" action="{{ route('empleados.store') }}">
            @csrf
            <div class="row g-3">
                {{-- DNI --}}
                <div class="col-md-4">
                    <label class="form-label">DNI *</label>
                    <input type="text" class="form-control @error('dni') is-invalid @enderror"
                        name="dni" value="{{ old('dni') }}" placeholder="12345678A" maxlength="9">
                    @error('dni')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <small class="text-muted">8 dígitos + letra (ej: 12345678A)</small>
                </div>

                {{-- Nombre --}}
                <div class="col-md-8">
                    <label class="form-label">Nombre Completo *</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                        name="nombre" value="{{ old('nombre') }}" placeholder="Juan García López">
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Email --}}
                <div class="col-md-6">
                    <label class="form-label">Correo Electrónico *</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" placeholder="juan@siemplecolgados.local">
                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <small class="text-muted">Debe ser único en el sistema</small>
                </div>

                {{-- Teléfono --}}
                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="tel" class="form-control @error('telefono') is-invalid @enderror"
                        name="telefono" value="{{ old('telefono') }}" placeholder="+34 612 345 678">
                    @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Dirección --}}
                <div class="col-12">
                    <label class="form-label">Dirección</label>
                    <input type="text" class="form-control @error('direccion') is-invalid @enderror"
                        name="direccion" value="{{ old('direccion') }}" placeholder="C/ Ejemplo 123, Huelva">
                    @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Fecha de Alta --}}
                <div class="col-md-4">
                    <label class="form-label">Fecha de Alta *</label>
                    <input type="date" class="form-control @error('fecha_alta') is-invalid @enderror"
                        name="fecha_alta" value="{{ old('fecha_alta', date('Y-m-d')) }}"
                        max="{{ date('Y-m-d') }}">
                    @error('fecha_alta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Tipo de Empleado --}}
                <div class="col-md-4">
                    <label class="form-label">Tipo de Empleado *</label>
                    <select class="form-select @error('tipo') is-invalid @enderror" name="tipo">
                        <option value="">Seleccionar...</option>
                        <option value="operario" {{ old('tipo')==='operario'?'selected':'' }}>🔧 Operario</option>
                        <option value="administrador" {{ old('tipo')==='administrador'?'selected':'' }}>🛡️ Administrador</option>
                    </select>
                    @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Estado --}}
                <div class="col-md-4">
                    <label class="form-label">Estado *</label>
                    <select class="form-select @error('activo') is-invalid @enderror" name="activo">
                        <option value="1" {{ old('activo', true)?'selected':'' }}>✅ Activo</option>
                        <option value="0" {{ old('activo', false)?'selected':'' }}>❌ Inactivo</option>
                    </select>
                    @error('activo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Contraseña (Opcional) --}}
                <div class="col-md-6">
                    <label class="form-label">Contraseña <span class="text-muted">(opcional)</span></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" placeholder="Dejar vacío para permitir login con Google">
                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <small class="text-muted">Mínimo 8 caracteres si se introduce</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirmar Contraseña</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                        name="password_confirmation" placeholder="Repetir si ha introducido contraseña">
                    @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- ℹ️ Nota sobre Google OAuth --}}
            <div class="alert alert-info mt-3 mb-0">
                <i class="fab fa-google me-2"></i>
                <strong>¿El empleado usará Google para iniciar sesión?</strong>
                <br><small class="text-muted">
                    Si deja la contraseña en blanco, el empleado podrá iniciar sesión con su cuenta de Google
                    siempre que el email coincida con el registrado aquí.
                </small>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('empleados.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Guardar Empleado
                </button>
            </div>
        </form>
    </div>
</div>
@endsection