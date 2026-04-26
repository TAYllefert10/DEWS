@extends('layouts.app')
@section('title', 'Nueva Cuota')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-plus-circle me-2 text-primary"></i>Nueva Cuota Excepcional</h2>
    <a href="{{ url('/cuotas') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Volver</a>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-info-circle me-2"></i>Datos de la Cuota</div>
    <div class="card-body">
        <form method="POST" action="{{ url('/cuotas') }}">
            @csrf
            <div class="row g-3">

                {{-- Cliente --}}
                <div class="col-md-6">
                    <label class="form-label">Cliente</label>
                    <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                        <option value="">Seleccionar cliente...</option>
                        @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ old('cliente_id')==$cliente->id?'selected':'' }}>
                            {{ $cliente->nombre }} ({{ $cliente->moneda }})
                        </option>
                        @endforeach
                    </select>
                    @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Concepto --}}
                <div class="col-md-6">
                    <label class="form-label">Concepto</label>
                    <input type="text" name="concepto" class="form-control @error('concepto') is-invalid @enderror"
                        value="{{ old('concepto') }}" placeholder="Ej: Reparación urgente de motor" required>
                    @error('concepto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Fecha Emisión --}}
                <div class="col-md-4">
                    <label class="form-label">Fecha de Emisión</label>
                    <input type="date" name="fecha_emision" class="form-control @error('fecha_emision') is-invalid @enderror"
                        value="{{ old('fecha_emision', date('Y-m-d')) }}" required>
                    @error('fecha_emision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Importe --}}
                <div class="col-md-4">
                    <label class="form-label">Importe</label>
                    <div class="input-group">
                        <span class="input-group-text">€</span>
                        <input type="number" name="importe" class="form-control @error('importe') is-invalid @enderror"
                            value="{{ old('importe', '0.00') }}" step="0.01" min="0" required>
                    </div>
                    @error('importe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Importe en la moneda del cliente</small>
                </div>

                {{-- Notas --}}
                <div class="col-12">
                    <label class="form-label">Notas (Opcional)</label>
                    <textarea name="notas" class="form-control @error('notas') is-invalid @enderror" rows="3">{{ old('notas') }}</textarea>
                    @error('notas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ url('/cuotas') }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Guardar Cuota</button>
            </div>
        </form>
    </div>
</div>
@endsection