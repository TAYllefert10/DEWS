@extends('layouts.app')
@section('title', 'Editar Cuota')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-edit me-2 text-warning"></i>Editar Cuota #{{ $cuota->id }}</h2>
    <a href="{{ url('/cuotas') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Volver</a>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-info-circle me-2"></i>Modificar Cuota</div>
    <div class="card-body">
        <form method="POST" action="{{ url('/cuotas/' . $cuota->id) }}">
            @csrf @method('PUT')
            <div class="row g-3">

                {{-- Cliente (solo lectura) --}}
                <div class="col-md-6">
                    <label class="form-label">Cliente</label>
                    <input type="text" class="form-control-plaintext fw-bold"
                        value="{{ $cuota->cliente->nombre }} ({{ $cuota->cliente->moneda }})" readonly>
                    <small class="text-muted">El cliente no se puede modificar</small>
                </div>

                {{-- Concepto --}}
                <div class="col-md-6">
                    <label class="form-label">Concepto</label>
                    <input type="text" name="concepto" class="form-control @error('concepto') is-invalid @enderror"
                        value="{{ old('concepto', $cuota->concepto) }}" required>
                    @error('concepto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Fecha Emisión --}}
                <div class="col-md-4">
                    <label class="form-label">Fecha de Emisión</label>
                    <input type="date" name="fecha_emision" class="form-control @error('fecha_emision') is-invalid @enderror"
                        value="{{ old('fecha_emision', $cuota->fecha_emision?->format('Y-m-d')) }}" required>
                    @error('fecha_emision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Importe --}}
                <div class="col-md-4">
                    <label class="form-label">Importe</label>
                    <div class="input-group">
                        <span class="input-group-text">{{ $cuota->cliente->moneda }}</span>
                        <input type="number" name="importe" class="form-control @error('importe') is-invalid @enderror"
                            value="{{ old('importe', $cuota->importe) }}" step="0.01" min="0" required>
                    </div>
                    @error('importe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Estado (solo lectura) --}}
                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <input type="text" class="form-control-plaintext"
                        value="{{ $cuota->pagada ? '✅ Pagada' : '⏳ Pendiente' }}" readonly>
                    @if($cuota->pagada)
                    <small class="text-muted text-warning">No se puede editar una cuota pagada</small>
                    @endif
                </div>

                {{-- Notas --}}
                <div class="col-12">
                    <label class="form-label">Notas</label>
                    <textarea name="notas" class="form-control @error('notas') is-invalid @enderror" rows="3">{{ old('notas', $cuota->notas) }}</textarea>
                    @error('notas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ url('/cuotas') }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancelar</a>
                <button type="submit" class="btn btn-warning"><i class="fas fa-save me-1"></i>Actualizar Cuota</button>
            </div>
        </form>
    </div>
</div>
@endsection