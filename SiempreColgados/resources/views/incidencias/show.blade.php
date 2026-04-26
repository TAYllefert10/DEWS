@extends('layouts.app')
@section('title', 'Detalle Incidencia')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-eye me-2 text-info"></i>Incidencia #{{ $incidencia->id }}</h2>
    <div>
        <a href="{{ url('/incidencias/' . $incidencia->id . '/edit') }}" class="btn btn-warning me-2"><i class="fas fa-edit me-1"></i>Editar</a>
        <a href="{{ url('/incidencias') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Volver</a>
    </div>
</div>

<div class="row">
    {{-- Columna Izquierda: Info General --}}
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-align-left me-2"></i>Descripción del Problema</div>
            <div class="card-body">
                <p class="lead">{{ $incidencia->descripcion }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><i class="fas fa-users me-2"></i>Contacto</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Persona:</strong><br>{{ $incidencia->persona_contacto }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Teléfono:</strong><br>{{ $incidencia->telefono_contacto ?? '—' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Correo:</strong><br>
                        @if($incidencia->correo)
                        <a href="mailto:{{ $incidencia->correo }}">{{ $incidencia->correo }}</a>
                        @else
                        —
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Columna Derecha: Estado y Archivos --}}
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i>Información</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Estado</span>
                        <span class="badge bg-{{ $incidencia->color_estado() }} fs-6">{{ $incidencia->nombre_estado() }}</span>
                    </li>
                    <li class="list-group-item">
                        <strong>Cliente:</strong><br>{{ $incidencia->cliente->nombre }}
                    </li>
                    <li class="list-group-item">
                        <strong>Operario Asignado:</strong><br>
                        {{ $incidencia->operario->nombre ?? 'Sin asignar' }}
                    </li>
                    <li class="list-group-item">
                        <strong>Creada:</strong><br>{{ $incidencia->created_at->format('d/m/Y H:i') }}
                    </li>
                    <li class="list-group-item">
                        <strong>Fecha Est. Realización:</strong><br>
                        {{ $incidencia->fecha_realizacion?->format('d/m/Y') ?? 'No definida' }}
                    </li>
                </ul>
            </div>
        </div>

        {{-- Para mostrar el nombre del archivo en el detalle --}}
        @if($incidencia->tieneFichero())
        <div class="card bg-light">
            <div class="card-body text-center">
                <i class="fas fa-paperclip fa-3x text-muted mb-3"></i>
                <h5 class="card-title">Archivo Adjunto</h5>
                <p class="text-muted mb-2">{{ $incidencia->nombreArchivoVisible() }}</p>
                <a href="{{ $incidencia->urlFichero() }}" class="btn btn-primary w-100" download>
                    <i class="fas fa-download me-1"></i>Descargar {{ $incidencia->nombreArchivoVisible() }}
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection