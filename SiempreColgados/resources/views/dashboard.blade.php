@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
@php
$user = auth()->user();
$esAdmin = $user->esAdministrador();

$stats = [
'total_incidencias' => \App\Models\Incidencia::count(),
'pendientes' => \App\Models\Incidencia::where('estado', 'P')->count(),
'en_proceso' => \App\Models\Incidencia::where('estado', 'E')->count(),
'realizadas' => \App\Models\Incidencia::where('estado', 'R')->count(),
];

if (!$esAdmin) {
$stats['mis_incidencias'] = \App\Models\Incidencia::where('operario_id', $user->id)->count();
$stats['mis_pendientes'] = \App\Models\Incidencia::where('operario_id', $user->id)
->where('estado', 'P')
->count();
}
@endphp

{{-- Mensaje de bienvenida según rol --}}
@if($esAdmin)
<div class="alert alert-info d-flex align-items-center">
    <i class="fas fa-crown me-2 fs-5"></i>
    <div>
        <strong>Panel de Administrador</strong>
        <p class="mb-0 small">Gestiona empleados, clientes, cuotas y asigna incidencias.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <a href="{{ url('/incidencias/create') }}" class="btn btn-primary w-100 py-3">
            <i class="fas fa-plus me-2"></i>Nueva incidencia
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ url('/clientes') }}" class="btn btn-outline-primary w-100 py-3">
            <i class="fas fa-building me-2"></i>Clientes
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ url('/empleados') }}" class="btn btn-outline-primary w-100 py-3">
            <i class="fas fa-users me-2"></i>Empleados
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{ url('/cuotas') }}" class="btn btn-outline-primary w-100 py-3">
            <i class="fas fa-file-invoice-dollar me-2"></i>Cuotas
        </a>
    </div>
</div>

@elseif($user->esOperario())
<div class="alert alert-success d-flex align-items-center">
    <i class="fas fa-user-check me-2 fs-5"></i>
    <div>
        <strong>Panel de Operario</strong>
        <p class="mb-0 small">Bienvenido, {{ $user->nombre }}. Gestiona tus incidencias asignadas.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="{{ url('/incidencias/create') }}" class="btn btn-primary w-100 py-3">
            <i class="fas fa-plus me-2"></i>Nueva incidencia
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ url('/incidencias') }}?estado=P" class="btn btn-outline-warning w-100 py-3">
            <i class="fas fa-hourglass-half me-2"></i>Mis pendientes ({{ $stats['mis_pendientes'] ?? 0 }})
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ url('/incidencias') }}?estado=R" class="btn btn-outline-success w-100 py-3">
            <i class="fas fa-check-circle me-2"></i>Historial
        </a>
    </div>
</div>

@else
<div class="alert alert-secondary d-flex align-items-center">
    <i class="fas fa-house me-2 fs-5"></i>
    <div>
        <strong>Bienvenido</strong>
        <p class="mb-0 small">Tu cuenta está en proceso de configuración. Contacta con administración.</p>
    </div>
</div>
@endif

{{-- Tarjetas de estadísticas --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                    <i class="fas fa-clipboard-list text-primary fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Total incidencias</div>
                    <div class="fs-4 fw-bold">{{ $stats['total_incidencias'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                    <i class="fas fa-hourglass-half text-warning fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Pendientes</div>
                    <div class="fs-4 fw-bold">{{ $stats['pendientes'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                    <i class="fas fa-spinner text-info fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">En proceso</div>
                    <div class="fs-4 fw-bold">{{ $stats['en_proceso'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                    <i class="fas fa-check-circle text-success fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Realizadas</div>
                    <div class="fs-4 fw-bold">{{ $stats['realizadas'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Incidencias recientes --}}
<div class="card border-0 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-semibold"><i class="fas fa-clock me-2 text-primary"></i>Incidencias recientes</span>
        {{-- ✅ CORREGIDO: Enlace explícito con url() --}}
        <a href="{{ url('/incidencias') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
    </div>
    <div class="card-body">
        @php
        $query = \App\Models\Incidencia::with('cliente');
        if (!$esAdmin) {
        $query->where('operario_id', $user->id);
        }
        $incs = $query->latest()->take(5)->get();
        @endphp

        @forelse($incs as $inc)
        <div class="d-flex align-items-center py-2 border-bottom">
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2">
                    <span class="fw-medium">#{{ $inc->id }}</span>
                    <span class="text-muted small">{{ Str::limit($inc->descripcion, 50) }}</span>
                </div>
                <div class="small text-muted mt-1">
                    <i class="fas fa-building me-1"></i>{{ $inc->cliente?->nombre ?? 'N/A' }}
                    <span class="mx-2">•</span>
                    <i class="fas fa-calendar me-1"></i>{{ $inc->created_at->format('d/m/Y') }}
                </div>
            </div>
            <span class="badge bg-{{ $inc->color_estado ?? 'secondary' }} ms-2">
                {{ $inc->nombre_estado ?? $inc->estado }}
            </span>
        </div>
        @empty
        <p class="text-muted mb-0 text-center py-4">
            <i class="fas fa-inbox fa-2x d-block mb-2 opacity-25"></i>
            No hay incidencias recientes
        </p>
        @endforelse
    </div>
</div>

{{-- Cuotas pendientes (solo para admin) --}}
@if($esAdmin)
<div class="card border-0 shadow-sm mt-4">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <span class="fw-semibold"><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Cuotas pendientes de pago</span>
        <a href="{{ url('/cuotas') }}?pagada=0" class="btn btn-sm btn-outline-primary">Ver todas</a>
    </div>
    <div class="card-body">
        @php
        $cuotas = \App\Models\Cuota::with('cliente')
        ->where('pagada', false)
        ->latest('fecha_emision')
        ->take(5)
        ->get();
        @endphp

        @forelse($cuotas as $cuota)
        <div class="d-flex align-items-center py-2 border-bottom">
            <div class="flex-grow-1">
                <div class="fw-medium">{{ $cuota->concepto ?? 'Cuota mensual' }}</div>
                <div class="small text-muted">
                    <i class="fas fa-building me-1"></i>{{ $cuota->cliente?->nombre ?? 'N/A' }}
                    <span class="mx-2">•</span>
                    <span class="text-primary fw-medium">{{ number_format($cuota->importe, 2) }} {{ $cuota->cliente?->moneda ?? '€' }}</span>
                </div>
            </div>
            <div class="text-end">
                <div class="small text-muted">{{ $cuota->fecha_emision?->format('d/m/Y') }}</div>

                {{-- ✅ CORREGIDO: Formulario POST para marcar pagada --}}
                <form method="POST" action="{{ url('/cuotas/' . $cuota->id . '/pagar') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success mt-1"
                        onclick="return confirm('¿Marcar esta cuota como pagada?')">
                        <i class="fas fa-check me-1"></i>Marcar pagada
                    </button>
                </form>

            </div>
        </div>
        @empty
        <p class="text-muted mb-0 text-center py-4">
            <i class="fas fa-check-circle fa-2x d-block mb-2 text-success opacity-25"></i>
            ¡Todas las cuotas están pagadas!
        </p>
        @endforelse
    </div>
</div>
@endif
@endsection