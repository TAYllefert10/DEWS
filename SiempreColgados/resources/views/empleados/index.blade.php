@extends('layouts.app')
@section('title', 'Empleados')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>Empleados</h2>
    <a href="{{ route('empleados.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Nuevo Empleado
    </a>
</div>

{{-- Filtros de búsqueda --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('empleados.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="buscar" class="form-control"
                    placeholder="Buscar por nombre, DNI o email..."
                    value="{{ request('buscar') }}">
            </div>
            <div class="col-md-3">
                <select name="tipo" class="form-select">
                    <option value="">Todos los tipos</option>
                    <option value="operario" {{ request('tipo')==='operario'?'selected':'' }}>🔧 Operario</option>
                    <option value="administrador" {{ request('tipo')==='administrador'?'selected':'' }}>🛡️ Administrador</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="activo" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="1" {{ request('activo')==='1'?'selected':'' }}>✅ Activos</option>
                    <option value="0" {{ request('activo')==='0'?'selected':'' }}>❌ Inactivos</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Filtrar
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="fas fa-list me-2"></i>Listado de Empleados</span>
        <span class="badge bg-primary">{{ $empleados->total() }} registros</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        {{-- DNI - Ordenable --}}
                        <th class="ps-4">
                            <a href="{{ route('empleados.index', array_merge(request()->except('sort','direction'), ['sort' => 'dni', 'direction' => ($sortBy ?? 'nombre') === 'dni' && ($sortDir ?? 'asc') === 'asc' ? 'desc' : 'asc'])) }}"
                                class="text-decoration-none text-dark">
                                DNI
                                @if(($sortBy ?? 'nombre') === 'dni')
                                <i class="fas fa-sort-{{ ($sortDir ?? 'asc') === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Nombre - Ordenable --}}
                        <th>
                            <a href="{{ route('empleados.index', array_merge(request()->except('sort','direction'), ['sort' => 'nombre', 'direction' => ($sortBy ?? 'nombre') === 'nombre' && ($sortDir ?? 'asc') === 'asc' ? 'desc' : 'asc'])) }}"
                                class="text-decoration-none text-dark">
                                Nombre
                                @if(($sortBy ?? 'nombre') === 'nombre')
                                <i class="fas fa-sort-{{ ($sortDir ?? 'asc') === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Email - Ordenable --}}
                        <th>
                            <a href="{{ route('empleados.index', array_merge(request()->except('sort','direction'), ['sort' => 'email', 'direction' => ($sortBy ?? 'nombre') === 'email' && ($sortDir ?? 'asc') === 'asc' ? 'desc' : 'asc'])) }}"
                                class="text-decoration-none text-dark">
                                Email
                                @if(($sortBy ?? 'nombre') === 'email')
                                <i class="fas fa-sort-{{ ($sortDir ?? 'asc') === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Teléfono - Ordenable --}}
                        <th>
                            <a href="{{ route('empleados.index', array_merge(request()->except('sort','direction'), ['sort' => 'telefono', 'direction' => ($sortBy ?? 'nombre') === 'telefono' && ($sortDir ?? 'asc') === 'asc' ? 'desc' : 'asc'])) }}"
                                class="text-decoration-none text-dark">
                                Teléfono
                                @if(($sortBy ?? 'nombre') === 'telefono')
                                <i class="fas fa-sort-{{ ($sortDir ?? 'asc') === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Tipo - Ordenable --}}
                        <th>
                            <a href="{{ route('empleados.index', array_merge(request()->except('sort','direction'), ['sort' => 'tipo', 'direction' => ($sortBy ?? 'nombre') === 'tipo' && ($sortDir ?? 'asc') === 'asc' ? 'desc' : 'asc'])) }}"
                                class="text-decoration-none text-dark">
                                Tipo
                                @if(($sortBy ?? 'nombre') === 'tipo')
                                <i class="fas fa-sort-{{ ($sortDir ?? 'asc') === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Método de Acceso --}}
                        <th>Acceso</th>

                        {{-- Fecha Alta - Ordenable --}}
                        <th>
                            <a href="{{ route('empleados.index', array_merge(request()->except('sort','direction'), ['sort' => 'fecha_alta', 'direction' => ($sortBy ?? 'nombre') === 'fecha_alta' && ($sortDir ?? 'asc') === 'asc' ? 'desc' : 'asc'])) }}"
                                class="text-decoration-none text-dark">
                                Fecha Alta
                                @if(($sortBy ?? 'nombre') === 'fecha_alta')
                                <i class="fas fa-sort-{{ ($sortDir ?? 'asc') === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
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
                    @forelse($empleados as $empleado)
                    <tr>
                        <td class="ps-4 fw-bold">{{ $empleado->dni }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <strong>{{ $empleado->nombre }}</strong>
                                @if(!$empleado->activo)
                                <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <a href="mailto:{{ $empleado->email }}" class="text-decoration-none">
                                <i class="fas fa-envelope me-1 text-muted"></i>{{ $empleado->email }}
                            </a>
                        </td>
                        <td>{{ $empleado->telefono ?? '—' }}</td>
                        <td>
                            @if($empleado->tipo === 'administrador')
                            <span class="badge bg-danger"><i class="fas fa-shield-alt me-1"></i>Administrador</span>
                            @else
                            <span class="badge bg-info text-dark"><i class="fas fa-wrench me-1"></i>Operario</span>
                            @endif
                        </td>
                        <td>
                            {{-- ✅ Badge de método de acceso --}}
                            @if($empleado->google_id)
                            <span class="badge bg-success" title="Autenticado con Google">
                                <i class="fab fa-google"></i>
                            </span>
                            @else
                            <span class="badge bg-secondary" title="Contraseña local">
                                <i class="fas fa-key"></i>
                            </span>
                            @endif
                        </td>
                        <td>{{ $empleado->fecha_alta ? $empleado->fecha_alta->format('d/m/Y') : '—' }}</td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('empleados.show', $empleado) }}" class="btn btn-outline-info" title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('empleados.edit', $empleado) }}" class="btn btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                {{-- No permitir dar de baja al propio empleado o último admin --}}
                                @if($empleado->id !== auth()->id() && !($empleado->esAdministrador() && \App\Models\Empleado::where('tipo','administrador')->where('activo',true)->count() <= 1))
                                    <form method="POST" action="{{ route('empleados.destroy', $empleado) }}" class="d-inline"
                                    onsubmit="return confirm('¿Dar de baja a {{ addslashes($empleado->nombre) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Dar de baja">
                                        <i class="fas fa-user-slash"></i>
                                    </button>
                                    </form>
                                    @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-users fa-3x mb-3 d-block opacity-25"></i>
                            <p class="mb-0">No hay empleados registrados</p>
                            <a href="{{ route('empleados.create') }}" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-plus me-1"></i>Crear primer empleado
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginación con parámetros de ordenación y filtros --}}
    @if($empleados->hasPages())
    <div class="card-footer">
        {{ $empleados->appends([
            'sort' => $sortBy, 
            'direction' => $sortDir,
            'buscar' => request('buscar'),
            'tipo' => request('tipo'),
            'activo' => request('activo'),
        ])->links() }}
    </div>
    @endif
</div>
@endsection