@extends('layouts.app')
@section('title', 'Editar Incidencia')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-edit me-2 text-warning"></i>Editar Incidencia #{{ $incidencia->id }}</h2>
    <a href="{{ url('/incidencias') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Volver</a>
</div>

<div class="card">
    <div class="card-header"><i class="fas fa-info-circle me-2"></i>Modificar Datos</div>
    <div class="card-body">

        <form method="POST" action="{{ url('/incidencias/' . $incidencia->id) }}" enctype="multipart/form-data" id="formEdit">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            {{-- Campo oculto para indicar si se debe eliminar el archivo --}}
            <input type="hidden" name="eliminar_fichero" id="eliminar_fichero" value="0">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Cliente</label>
                    <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror">
                        @foreach($clientes as $c)
                        <option value="{{ $c->id }}" {{ old('cliente_id', $incidencia->cliente_id)==$c->id?'selected':'' }}>{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                    @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Operario</label>
                    <select name="operario_id" class="form-select @error('operario_id') is-invalid @enderror">
                        <option value="">Sin asignar</option>
                        @foreach($operarios as $o)
                        <option value="{{ $o->id }}" {{ old('operario_id', $incidencia->operario_id)==$o->id?'selected':'' }}>{{ $o->nombre }}</option>
                        @endforeach
                    </select>
                    @error('operario_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contacto</label>
                    <input type="text" name="persona_contacto" class="form-control @error('persona_contacto') is-invalid @enderror" value="{{ old('persona_contacto', $incidencia->persona_contacto) }}">
                    @error('persona_contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono_contacto" class="form-control @error('telefono_contacto') is-invalid @enderror" value="{{ old('telefono_contacto', $incidencia->telefono_contacto) }}">
                    @error('telefono_contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Correo</label>
                    <input type="text" name="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo', $incidencia->correo) }}">
                    @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                        <option value="P" {{ old('estado', $incidencia->estado)==='P'?'selected':'' }}>Pendiente</option>
                        <option value="E" {{ old('estado', $incidencia->estado)==='E'?'selected':'' }}>En Proceso</option>
                        <option value="R" {{ old('estado', $incidencia->estado)==='R'?'selected':'' }}>Realizada</option>
                        <option value="C" {{ old('estado', $incidencia->estado)==='C'?'selected':'' }}>Cancelada</option>
                    </select>
                    @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="4">{{ old('descripcion', $incidencia->descripcion) }}</textarea>
                    @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fecha Realización</label>
                    <input type="text"
                        name="fecha_realizacion"
                        class="form-control @error('fecha_realizacion') is-invalid @enderror"
                        value="{{ old('fecha_realizacion', $incidencia->fecha_realizacion?->format('Y-m-d')) }}"
                        id="fecha_realizacion_edit"
                        autocomplete="off">
                    @error('fecha_realizacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- ✅ Gestión de archivo con eliminar y mostrar nombre original --}}
                <div class="col-md-6">
                    <label class="form-label">Archivo Adjunto</label>

                    @if($incidencia->fichero)
                    <div class="border rounded p-2 mb-2 bg-light">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <i class="fas fa-file-alt me-2 text-primary"></i>
                                <strong>{{ $incidencia->nombreArchivoVisible() }}</strong>
                                <small class="text-muted d-block">{{ number_format(Storage::disk('public')->size($incidencia->fichero)/1024, 1) }} KB</small>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="{{ url('/incidencias/' . $incidencia->id . '/fichero') }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Descargar">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarArchivo()" title="Eliminar archivo">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                    {{-- Input para subir nuevo archivo --}}
                    <input type="file" name="fichero" class="form-control @error('fichero') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.zip" id="input_fichero">
                    <small class="text-muted d-block mt-1">Formatos: jpg, png, pdf, doc, zip (Max 10MB)</small>
                    <small class="text-muted">Dejar vacío para mantener el archivo actual</small>

                    @error('fichero')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ url('/incidencias') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-warning">Actualizar</button>
            </div>
        </form>

    </div>
</div>

{{-- Script para eliminar archivo + Flatpickr --}}
@push('scripts')
<script>
    // ✅ Función para eliminar archivo
    function eliminarArchivo() {
        if (confirm('¿Eliminar el archivo adjunto de esta incidencia?')) {
            document.getElementById('eliminar_fichero').value = '1';
            document.getElementById('input_fichero').value = '';
            // Ocultar visualmente el archivo actual
            const fileBox = document.querySelector('.border.rounded.p-2');
            if (fileBox) fileBox.style.display = 'none';
        }
    }

    // ✅ Flatpickr para edit: permite cualquier fecha (históricas incluidas)
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#fecha_realizacion_edit", {
            locale: "es",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
            // ❌ Sin minDate: permite editar fechas pasadas en edición
            allowInput: true,
            disableMobile: true
        });
    });
</script>
@endpush
@endsection