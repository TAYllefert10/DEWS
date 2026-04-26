@extends('layouts.app')
@section('title', 'Incidencias')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-clipboard-list me-2 text-primary"></i>Incidencias</h2>
    <a href="{{ url('/incidencias/create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Nueva Incidencia
    </a>
</div>

{{-- Filtros de búsqueda --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ url('/incidencias') }}" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="buscar" class="form-control" placeholder="Buscar por cliente o descripción..." value="{{ request('buscar') }}">
            </div>
            <div class="col-md-3">
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="P" {{ request('estado')==='P'?'selected':'' }}>🟡 Pendiente</option>
                    <option value="E" {{ request('estado')==='E'?'selected':'' }}>🔵 En Proceso</option>
                    <option value="R" {{ request('estado')==='R'?'selected':'' }}>🟢 Realizada</option>
                    <option value="C" {{ request('estado')==='C'?'selected':'' }}>🔴 Cancelada</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-fill"><i class="fas fa-search"></i> Filtrar</button>
                    <a href="{{ url('/incidencias?' . http_build_query(['sort' => $sortBy ?? 'created_at', 'direction' => $sortDir ?? 'desc'])) }}" class="btn btn-outline-secondary flex-fill"><i class="fas fa-times"></i> Limpiar</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabla de resultados --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="fas fa-list me-2"></i>Listado de Incidencias</span>
        <span class="badge bg-primary">{{ $incidencias->total() }} registros</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        {{-- ID - Ordenable --}}
                        <th class="ps-4">
                            <a href="{{ url('/incidencias?' . http_build_query(array_merge(request()->except('sort','direction'), ['sort' => 'id', 'direction' => ($sortBy ?? 'created_at') === 'id' && ($sortDir ?? 'desc') === 'asc' ? 'desc' : 'asc']))) }}"
                                class="text-decoration-none text-dark">
                                ID
                                @if(($sortBy ?? 'created_at') === 'id')
                                <i class="fas fa-sort-{{ ($sortDir ?? 'desc') === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Cliente - Ordenable por cliente_id --}}
                        <th>
                            <a href="{{ url('/incidencias?' . http_build_query(array_merge(request()->except('sort','direction'), ['sort' => 'cliente_id', 'direction' => ($sortBy ?? 'created_at') === 'cliente_id' && ($sortDir ?? 'desc') === 'asc' ? 'desc' : 'asc']))) }}"
                                class="text-decoration-none text-dark">
                                Cliente
                                @if(($sortBy ?? 'created_at') === 'cliente_id')
                                <i class="fas fa-sort-{{ ($sortDir ?? 'desc') === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Descripción - Ordenable --}}
                        <th>
                            <a href="{{ url('/incidencias?' . http_build_query(array_merge(request()->except('sort','direction'), ['sort' => 'descripcion', 'direction' => ($sortBy ?? 'created_at') === 'descripcion' && ($sortDir ?? 'desc') === 'asc' ? 'desc' : 'asc']))) }}"
                                class="text-decoration-none text-dark">
                                Descripción
                                @if(($sortBy ?? 'created_at') === 'descripcion')
                                <i class="fas fa-sort-{{ ($sortDir ?? 'desc') === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Operario - Ordenable por operario_id --}}
                        <th>
                            <a href="{{ url('/incidencias?' . http_build_query(array_merge(request()->except('sort','direction'), ['sort' => 'operario_id', 'direction' => ($sortBy ?? 'created_at') === 'operario_id' && ($sortDir ?? 'desc') === 'asc' ? 'desc' : 'asc']))) }}"
                                class="text-decoration-none text-dark">
                                Operario
                                @if(($sortBy ?? 'created_at') === 'operario_id')
                                <i class="fas fa-sort-{{ ($sortDir ?? 'desc') === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Estado - Ordenable --}}
                        <th>
                            <a href="{{ url('/incidencias?' . http_build_query(array_merge(request()->except('sort','direction'), ['sort' => 'estado', 'direction' => ($sortBy ?? 'created_at') === 'estado' && ($sortDir ?? 'desc') === 'asc' ? 'desc' : 'asc']))) }}"
                                class="text-decoration-none text-dark">
                                Estado
                                @if(($sortBy ?? 'created_at') === 'estado')
                                <i class="fas fa-sort-{{ ($sortDir ?? 'desc') === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Archivo - NO ordenable --}}
                        <th>Archivo</th>

                        {{-- Fecha - Ordenable por created_at --}}
                        <th>
                            <a href="{{ url('/incidencias?' . http_build_query(array_merge(request()->except('sort','direction'), ['sort' => 'created_at', 'direction' => ($sortBy ?? 'created_at') === 'created_at' && ($sortDir ?? 'desc') === 'asc' ? 'desc' : 'asc']))) }}"
                                class="text-decoration-none text-dark">
                                Fecha
                                @if(($sortBy ?? 'created_at') === 'created_at')
                                <i class="fas fa-sort-{{ ($sortDir ?? 'desc') === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Acciones - NO ordenable --}}
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incidencias as $inc)
                    <tr>
                        <td class="ps-4 fw-bold">#{{ $inc->id }}</td>
                        <td>{{ $inc->cliente->nombre ?? 'N/A' }}</td>
                        <td>
                            <span class="d-block text-truncate" style="max-width: 200px;" title="{{ $inc->descripcion }}">
                                {{ Str::limit($inc->descripcion, 40) }}
                            </span>
                        </td>

                        {{-- ✅ CORREGIDO: Operario con renderizado seguro de HTML --}}
                        <td>
                            @if($inc->operario)
                            {{ $inc->operario->nombre }}
                            @else
                            <span class="text-muted">Sin asignar</span>
                            @endif
                        </td>

                        <td>
                            <span class="badge bg-{{ $inc->color_estado() }}">
                                {{ $inc->nombre_estado() }}
                            </span>
                        </td>

                        {{-- Archivo con nombre y enlace --}}
                        <td>
                            @if($inc->fichero)
                            <a href="{{ url('/incidencias/' . $inc->id . '/fichero') }}" class="text-decoration-none" title="Descargar {{ $inc->nombreArchivoVisible() }}" target="_blank">
                                <i class="fas fa-file-alt me-1 text-primary"></i>
                                <span class="d-none d-md-inline">{{ Str::limit($inc->nombreArchivoVisible(), 20) }}</span>
                            </a>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td>{{ $inc->created_at->format('d/m/Y') }}</td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ url('/incidencias/' . $inc->id) }}" class="btn btn-outline-info" title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ url('/incidencias/' . $inc->id . '/edit') }}" class="btn btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(auth()->user()->isAdmin())
                                <form method="POST" action="{{ url('/incidencias/' . $inc->id) }}" class="d-inline"
                                    onsubmit="return confirm('¿Cancelar esta incidencia? (Se marcará como cerrada)')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Cancelar">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 d-block opacity-25"></i>
                            <p class="mb-0">No hay incidencias registradas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginación con filtros + búsqueda + ordenación --}}
    @if($incidencias->hasPages())
    <div class="card-footer">
        {{ $incidencias->appends([
            'sort' => $sortBy,
            'direction' => $sortDir,
            'estado' => request('estado'),
            'buscar' => request('buscar'),
        ])->links() }}
    </div>
    @endif
</div>
@endsection