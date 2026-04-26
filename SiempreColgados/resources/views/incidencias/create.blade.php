@extends('layouts.app')
@section('title', 'Nueva Incidencia')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-plus-circle me-2 text-primary"></i>Nueva Incidencia</h2>
    <a href="{{ url('/incidencias') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Volver</a>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-info-circle me-2"></i>Datos de la Incidencia</div>
    <div class="card-body">
        <form method="POST" action="{{ url('/incidencias') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">

                {{-- Cliente --}}
                <div class="col-md-6">
                    <label class="form-label">Cliente Afectado</label>
                    <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror">
                        <option value="">Seleccionar cliente...</option>
                        @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ old('cliente_id')==$cliente->id?'selected':'' }}>
                            {{ $cliente->nombre }}
                        </option>
                        @endforeach
                    </select>
                    @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Operario --}}
                <div class="col-md-6">
                    <label class="form-label">Asignar Operario</label>
                    <select name="operario_id" class="form-select @error('operario_id') is-invalid @enderror">
                        <option value="">Sin asignar</option>
                        @foreach($operarios as $op)
                        <option value="{{ $op->id }}" {{ old('operario_id')==$op->id?'selected':'' }}>
                            {{ $op->nombre }}
                        </option>
                        @endforeach
                    </select>
                    @error('operario_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Persona Contacto --}}
                <div class="col-md-6">
                    <label class="form-label">Persona de Contacto</label>
                    <input type="text" name="persona_contacto" class="form-control @error('persona_contacto') is-invalid @enderror" value="{{ old('persona_contacto') }}">
                    @error('persona_contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Teléfono --}}
                <div class="col-md-6">
                    <label class="form-label">Teléfono de Contacto</label>
                    <input type="text" name="telefono_contacto" class="form-control @error('telefono_contacto') is-invalid @enderror" value="{{ old('telefono_contacto') }}">
                    @error('telefono_contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Correo --}}
                <div class="col-md-6">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="text" name="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo') }}">
                    @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Estado --}}
                <div class="col-md-6">
                    <label class="form-label">Estado Inicial</label>
                    <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                        <option value="P" {{ old('estado')==='P'?'selected':'' }}>🟡 Pendiente</option>
                        <option value="E" {{ old('estado')==='E'?'selected':'' }}>🔵 En Proceso</option>
                        <option value="R" {{ old('estado')==='R'?'selected':'' }}>🟢 Realizada</option>
                        <option value="C" {{ old('estado')==='C'?'selected':'' }}>🔴 Cancelada</option>
                    </select>
                    @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Descripción --}}
                <div class="col-12">
                    <label class="form-label">Descripción de la Incidencia</label>
                    <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="4">{{ old('descripcion') }}</textarea>
                    @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Fecha Realización --}}
                <div class="col-md-6">
                    <label class="form-label">Fecha Estimada de Realización</label>
                    <input type="text"
                        name="fecha_realizacion"
                        class="form-control @error('fecha_realizacion') is-invalid @enderror"
                        value="{{ old('fecha_realizacion') }}"
                        placeholder=""
                        id="fecha_realizacion_create"
                        autocomplete="off">
                    <small class="text-muted">No se pueden seleccionar fechas anteriores a hoy</small>
                    @error('fecha_realizacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Fichero Adjunto --}}
                <div class="col-md-6">
                    <label class="form-label">Adjuntar Archivo (Opcional)</label>
                    <input type="file" name="fichero" class="form-control @error('fichero') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.zip">
                    <small class="text-muted">Formatos: jpg, png, pdf, doc, zip (Max 10MB)</small>
                    @error('fichero')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

            </div>
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ url('/incidencias') }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancelar</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Guardar Incidencia</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 📅 Flatpickr para create: bloquea fechas pasadas
        flatpickr("#fecha_realizacion_create", {
            locale: "es",
            dateFormat: "Y-m-d", // Formato que se envía a Laravel
            altInput: true, // Mostrar formato amigable al usuario
            altFormat: "d/m/Y", // Formato visible: 25/12/2024
            minDate: "today", // 🔒 Bloquea fechas anteriores a hoy
            allowInput: true, // Permitir escritura manual
            disableMobile: true // Usar Flatpickr también en móviles
        });
    });
</script>
@endpush