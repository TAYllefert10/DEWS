@extends('layouts.app')
@section('title', 'Nuevo Cliente')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-user-plus me-2 text-primary"></i>Nuevo Cliente</h2>
    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Volver al listado
    </a>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-info-circle me-2"></i>Información del Cliente</div>
    <div class="card-body">
        <form method="POST" action="{{ route('clientes.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">CIF</label>
                    <input type="text" class="form-control @error('cif') is-invalid @enderror" name="cif" value="{{ old('cif') }}" placeholder="B12345678">
                    @error('cif')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label">Razón Social</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" placeholder="Empresa Ejemplo S.L.">
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" value="{{ old('telefono') }}" placeholder="+34 612 345 678">
                    @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="text" class="form-control @error('correo') is-invalid @enderror" name="correo" value="{{ old('correo') }}" placeholder="contacto@empresa.local">
                    @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Cuenta Corriente (IBAN)</label>
                    <input type="text" class="form-control @error('cuenta_corriente') is-invalid @enderror" name="cuenta_corriente" value="{{ old('cuenta_corriente') }}" placeholder="ES12 3456 7890 1234 5678 9012">
                    @error('cuenta_corriente')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">País</label>
                    <select class="form-select @error('pais') is-invalid @enderror" name="pais">
                        <option value="">Seleccionar opción...</option>
                        <option value="ES" {{ old('pais')==='ES'?'selected':'' }}>🇪 España</option>
                        <option value="PT" {{ old('pais')==='PT'?'selected':'' }}>🇵🇹 Portugal</option>
                        <option value="FR" {{ old('pais')==='FR'?'selected':'' }}>🇫🇷 Francia</option>
                        <option value="DE" {{ old('pais')==='DE'?'selected':'' }}>🇩🇪 Alemania</option>
                        <option value="US" {{ old('pais')==='US'?'selected':'' }}>🇺🇸 Estados Unidos</option>
                    </select>
                    @error('pais')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Moneda</label>
                    <select class="form-select @error('moneda') is-invalid @enderror" name="moneda">
                        <option value="">Seleccionar opción...</option>
                        <option value="EUR" {{ old('moneda')==='EUR'?'selected':'' }}>€ Euro</option>
                        <option value="USD" {{ old('moneda')==='USD'?'selected':'' }}>$ Dólar</option>
                        <option value="GBP" {{ old('moneda')==='GBP'?'selected':'' }}>£ Libra</option>
                    </select>
                    @error('moneda')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cuota Mensual</label>
                    <div class="input-group">
                        <span class="input-group-text">€</span>
                        <input type="text" class="form-control @error('importe_cuota') is-invalid @enderror" name="importe_cuota" value="{{ old('importe_cuota', '0.00') }}">
                    </div>
                    @error('importe_cuota')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Guardar Cliente</button>
            </div>
        </form>
    </div>
</div>
@endsection