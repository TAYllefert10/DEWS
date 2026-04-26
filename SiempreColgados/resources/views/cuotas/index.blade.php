@extends('layouts.app')
@section('title', 'Cuotas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Cuotas</h2>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRemesa">
            <i class="fas fa-cash-register me-1"></i>Generar Remesa Mensual
        </button>
        <a href="{{ url('/cuotas/create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Nueva Cuota
        </a>
    </div>
</div>

{{-- Filtros --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ url('/cuotas') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small">Cliente</label>
                <select name="cliente_id" class="form-select form-select-sm">
                    <option value="">Todos los clientes</option>
                    @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ request('cliente_id')==$cliente->id?'selected':'' }}>
                        {{ $cliente->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Estado</label>
                <select name="pagada" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="1" {{ request('pagada')==='1'?'selected':'' }}>✅ Pagada</option>
                    <option value="0" {{ request('pagada')==='0'?'selected':'' }}>⏳ Pendiente</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small">Desde</label>
                <input type="date" name="fecha_desde" class="form-control form-control-sm" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Hasta</label>
                <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-outline-primary btn-sm flex-fill"><i class="fas fa-search"></i> Filtrar</button>
                <a href="{{ url('/cuotas') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times"></i></a>
            </div>
        </form>
    </div>
</div>

{{-- Tabla --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="fas fa-list me-2"></i>Listado de Cuotas</span>
        <span class="badge bg-primary">{{ $cuotas->total() }} registros</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        {{-- ID - Ordenable --}}
                        <th class="ps-4">
                            <a href="{{ url('/cuotas?' . http_build_query(array_merge(request()->except('sort','direction'), ['sort' => 'id', 'direction' => ($sortBy ?? 'fecha_emision') === 'id' && ($sortDir ?? 'desc') === 'asc' ? 'desc' : 'asc']))) }}"
                                class="text-decoration-none text-dark">
                                ID
                                @if(($sortBy ?? 'fecha_emision') === 'id')
                                <i class="fas fa-sort-{{ ($sortDir ?? 'desc') === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Cliente - Ordenable por cliente_id --}}
                        <th>
                            <a href="{{ url('/cuotas?' . http_build_query(array_merge(request()->except('sort','direction'), ['sort' => 'cliente_id', 'direction' => ($sortBy ?? 'fecha_emision') === 'cliente_id' && ($sortDir ?? 'desc') === 'asc' ? 'desc' : 'asc']))) }}"
                                class="text-decoration-none text-dark">
                                Cliente
                                @if(($sortBy ?? 'fecha_emision') === 'cliente_id')
                                <i class="fas fa-sort-{{ ($sortDir ?? 'desc') === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Concepto - Ordenable --}}
                        <th>
                            <a href="{{ url('/cuotas?' . http_build_query(array_merge(request()->except('sort','direction'), ['sort' => 'concepto', 'direction' => ($sortBy ?? 'fecha_emision') === 'concepto' && ($sortDir ?? 'desc') === 'asc' ? 'desc' : 'asc']))) }}"
                                class="text-decoration-none text-dark">
                                Concepto
                                @if(($sortBy ?? 'fecha_emision') === 'concepto')
                                <i class="fas fa-sort-{{ ($sortDir ?? 'desc') === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Fecha - Ordenable --}}
                        <th>
                            <a href="{{ url('/cuotas?' . http_build_query(array_merge(request()->except('sort','direction'), ['sort' => 'fecha_emision', 'direction' => ($sortBy ?? 'fecha_emision') === 'fecha_emision' && ($sortDir ?? 'desc') === 'asc' ? 'desc' : 'asc']))) }}"
                                class="text-decoration-none text-dark">
                                Fecha
                                @if(($sortBy ?? 'fecha_emision') === 'fecha_emision')
                                <i class="fas fa-sort-{{ ($sortDir ?? 'desc') === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Importe - Ordenable --}}
                        <th>
                            <a href="{{ url('/cuotas?' . http_build_query(array_merge(request()->except('sort','direction'), ['sort' => 'importe', 'direction' => ($sortBy ?? 'fecha_emision') === 'importe' && ($sortDir ?? 'desc') === 'asc' ? 'desc' : 'asc']))) }}"
                                class="text-decoration-none text-dark">
                                Importe
                                @if(($sortBy ?? 'fecha_emision') === 'importe')
                                <i class="fas fa-sort-{{ ($sortDir ?? 'desc') === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                @else
                                <i class="fas fa-sort text-muted ms-1"></i>
                                @endif
                            </a>
                        </th>

                        {{-- Estado - Ordenable por pagada --}}
                        <th>
                            <a href="{{ url('/cuotas?' . http_build_query(array_merge(request()->except('sort','direction'), ['sort' => 'pagada', 'direction' => ($sortBy ?? 'fecha_emision') === 'pagada' && ($sortDir ?? 'desc') === 'asc' ? 'desc' : 'asc']))) }}"
                                class="text-decoration-none text-dark">
                                Estado
                                @if(($sortBy ?? 'fecha_emision') === 'pagada')
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
                    @forelse($cuotas as $cuota)
                    <tr>
                        <td class="ps-4 fw-bold">#{{ $cuota->id }}</td>
                        <td>{{ $cuota->cliente->nombre ?? 'N/A' }}</td>
                        <td>
                            <span class="d-block text-truncate" style="max-width: 250px;" title="{{ $cuota->concepto }}">
                                {{ Str::limit($cuota->concepto, 40) }}
                            </span>
                        </td>
                        <td>{{ $cuota->fecha_emision?->format('d/m/Y') }}</td>
                        <td>
                            <strong>{{ number_format($cuota->importe, 2, ',', '.') }} {{ $cuota->cliente->moneda }}</strong>
                            @if($cuota->cliente->moneda !== 'EUR' && $cuota->importe_euros)
                            <br><small class="text-muted">≈ {{ number_format($cuota->importe_euros, 2, ',', '.') }} €</small>
                            @endif
                        </td>
                        <td>
                            @if($cuota->pagada)
                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Pagada</span>
                            @else
                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pendiente</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ url('/cuotas/' . $cuota->id . '/factura') }}" class="btn btn-outline-info" title="Ver factura" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ url('/cuotas/' . $cuota->id . '/factura/descargar') }}" class="btn btn-outline-secondary" title="Descargar factura" download>
                                    <i class="fas fa-download"></i>
                                </a>
                                @if(!$cuota->pagada)
                                <a href="{{ url('/cuotas/' . $cuota->id . '/edit') }}" class="btn btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ url('/cuotas/' . $cuota->id . '/pagar') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success" title="Marcar como pagada" onclick="return confirm('¿Marcar esta cuota como PAGADA?')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ url('/cuotas/' . $cuota->id) }}" class="d-inline" onsubmit="return confirm('¿Eliminar esta cuota? Esta acción no se puede deshacer.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                                <form method="POST" action="{{ url('/cuotas/' . $cuota->id . '/factura/enviar') }}" class="d-inline" onsubmit="return confirm('¿Enviar factura por email a {{ addslashes($cuota->cliente->correo) }}?')">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary" title="Enviar por email">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-file-invoice-dollar fa-3x mb-3 d-block opacity-25"></i>
                            <p class="mb-0">No hay cuotas registradas</p>
                            <a href="{{ url('/cuotas/create') }}" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-plus me-1"></i>Crear primera cuota
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginación con filtros + ordenación --}}
    @if($cuotas->hasPages())
    <div class="card-footer">
        {{ $cuotas->appends([
            'sort' => $sortBy,
            'direction' => $sortDir,
            'cliente_id' => request('cliente_id'),
            'pagada' => request('pagada'),
            'fecha_desde' => request('fecha_desde'),
            'fecha_hasta' => request('fecha_hasta'),
        ])->links() }}
    </div>
    @endif
</div>

{{-- Modal: Generar Remesa Mensual (igual que antes) --}}
<div class="modal fade" id="modalRemesa" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ url('/cuotas/remesa') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-cash-register me-2"></i>Generar Remesa Mensual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">
                        Esta acción creará una cuota mensual para cada cliente activo que no tenga ya una cuota registrada para el mes seleccionado.
                    </p>
                    <div class="mb-3">
                        <label class="form-label">Fecha de Emisión</label>
                        <input type="date" name="fecha_emision" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle me-1"></i>
                        Se crearán cuotas por el importe mensual definido en cada cliente ({{ $clientes->count() }} clientes activos).
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-cash-register me-1"></i>Generar Remesa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection