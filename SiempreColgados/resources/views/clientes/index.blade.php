@extends('layouts.app')
@section('title', 'Clientes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-building me-2 text-primary"></i>Clientes</h2>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Nuevo Cliente
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="fas fa-list me-2"></i>Listado de Clientes</span>
        <span class="badge bg-primary">{{ $clientes->total() }} registros</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        {{-- CIF - Ordenable --}}
                        <th class="ps-4">
                            <a href="{{ route('clientes.index', array_merge(request()->except('sort','direction'), ['sort' => 'cif', 'direction' => $sortBy === 'cif' && $sortDir === 'asc' ? 'desc' : 'asc'])) }}"
                                class="text-decoration-none text-dark">
                                CIF
                                @if($sortBy === 'cif')
                                <i class="fas fa-sort-{{ $sortDir === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Nombre - Ordenable --}}
                        <th>
                            <a href="{{ route('clientes.index', array_merge(request()->except('sort','direction'), ['sort' => 'nombre', 'direction' => $sortBy === 'nombre' && $sortDir === 'asc' ? 'desc' : 'asc'])) }}"
                                class="text-decoration-none text-dark">
                                Nombre
                                @if($sortBy === 'nombre')
                                <i class="fas fa-sort-{{ $sortDir === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Teléfono - Ordenable --}}
                        <th>
                            <a href="{{ route('clientes.index', array_merge(request()->except('sort','direction'), ['sort' => 'telefono', 'direction' => $sortBy === 'telefono' && $sortDir === 'asc' ? 'desc' : 'asc'])) }}"
                                class="text-decoration-none text-dark">
                                Teléfono
                                @if($sortBy === 'telefono')
                                <i class="fas fa-sort-{{ $sortDir === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Correo - Ordenable --}}
                        <th>
                            <a href="{{ route('clientes.index', array_merge(request()->except('sort','direction'), ['sort' => 'correo', 'direction' => $sortBy === 'correo' && $sortDir === 'asc' ? 'desc' : 'asc'])) }}"
                                class="text-decoration-none text-dark">
                                Correo
                                @if($sortBy === 'correo')
                                <i class="fas fa-sort-{{ $sortDir === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- País - Ordenable --}}
                        <th>
                            <a href="{{ route('clientes.index', array_merge(request()->except('sort','direction'), ['sort' => 'pais', 'direction' => $sortBy === 'pais' && $sortDir === 'asc' ? 'desc' : 'asc'])) }}"
                                class="text-decoration-none text-dark">
                                País
                                @if($sortBy === 'pais')
                                <i class="fas fa-sort-{{ $sortDir === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Cuota - Ordenable --}}
                        <th>
                            <a href="{{ route('clientes.index', array_merge(request()->except('sort','direction'), ['sort' => 'importe_cuota', 'direction' => $sortBy === 'importe_cuota' && $sortDir === 'asc' ? 'desc' : 'asc'])) }}"
                                class="text-decoration-none text-dark">
                                Cuota
                                @if($sortBy === 'importe_cuota')
                                <i class="fas fa-sort-{{ $sortDir === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
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
                    @forelse($clientes as $cliente)
                    <tr>
                        <td class="ps-4 fw-bold">{{ $cliente->cif }}</td>
                        <td>
                            <strong>{{ $cliente->nombre }}</strong>
                            @if(!$cliente->activo)<span class="badge bg-secondary ms-2">Inactivo</span>@endif
                        </td>
                        <td>{{ $cliente->telefono ?? '—' }}</td>
                        <td>
                            @if($cliente->correo)
                            <a href="mailto:{{ $cliente->correo }}" class="text-decoration-none">
                                <i class="fas fa-envelope me-1 text-muted"></i>{{ $cliente->correo }}
                            </a>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ strtoupper($cliente->pais) }}</span></td>
                        <td><strong>{{ number_format($cliente->importe_cuota, 2, ',', '.') }} {{ $cliente->moneda }}</strong></td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                @if(!$cliente->activo)
                                <form method="POST" action="{{ route('clientes.alta', $cliente) }}" class="d-inline"
                                    onsubmit="return confirm('¿Dar de ALTA a {{ addslashes($cliente->nombre) }}?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-outline-success" title="Dar de alta">
                                        <i class="fas fa-user-check"></i>
                                    </button>
                                </form>
                                @endif

                                @if($cliente->activo)
                                <form method="POST" action="{{ route('clientes.destroy', $cliente) }}" class="d-inline"
                                    onsubmit="return confirm('¿Dar de BAJA a {{ addslashes($cliente->nombre) }}?')">
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
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-building fa-3x mb-3 d-block opacity-25"></i>
                            <p class="mb-0">No hay clientes registrados</p>
                            <a href="{{ route('clientes.create') }}" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-plus me-1"></i>Crear primer cliente
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginación con parámetros de ordenación --}}
    @if($clientes->hasPages())
    <div class="card-footer">
        {{ $clientes->appends(['sort' => $sortBy, 'direction' => $sortDir])->links() }}
    </div>
    @endif
</div>
@endsection