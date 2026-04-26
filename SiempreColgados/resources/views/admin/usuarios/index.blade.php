@extends('layouts.app')
@section('title', 'Gestión de Usuarios')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>Usuarios del Sistema</h2>
    <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Volver
    </a>
</div>

{{-- Filtros --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('usuarios.index') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="buscar" class="form-control"
                    placeholder="Buscar por nombre o email..."
                    value="{{ request('buscar') }}">
            </div>
            <div class="col-md-3">
                <select name="auth_method" class="form-select">
                    <option value="">Todos los métodos</option>
                    <option value="google" {{ request('auth_method')==='google'?'selected':'' }}>🔵 Google</option>
                    <option value="password" {{ request('auth_method')==='password'?'selected':'' }}>🔑 Contraseña</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">Todos los roles</option>
                    <option value="admin" {{ request('role')==='admin'?'selected':'' }}>👑 Administrador</option>
                    <option value="operario" {{ request('role')==='operario'?'selected':'' }}>🔧 Operario</option>
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

{{-- Mensajes de feedback --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Tabla de usuarios --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="fas fa-list me-2"></i>Listado de Usuarios</span>
        <span class="badge bg-primary">{{ $usuarios->total() }} registros</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Método de Acceso</th>
                        <th>Rol</th>
                        <th>Empleado Vinculado</th>
                        <th>Fecha Registro</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $user)
                    <tr>
                        <td class="ps-4 fw-bold">#{{ $user->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($user->avatar)
                                <img src="{{ $user->avatar }}" alt="" class="rounded-circle" width="32" height="32">
                                @else
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;font-size:12px;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                @endif
                                <span>{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->google_id)
                            <span class="badge bg-success">
                                <i class="fab fa-google me-1"></i>Google
                            </span>
                            @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-key me-1"></i>Contraseña
                            </span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->isAdmin() ? 'danger' : 'info' }}">
                                {{ $user->getRoleNameAttribute() }}
                            </span>
                        </td>
                        <td>
                            @if($user->empleado)
                            <span class="text-success" title="{{ $user->empleado->dni }}">
                                <i class="fas fa-check-circle me-1"></i>
                                {{ Str::limit($user->empleado->nombre, 20) }}
                            </span>
                            @else
                            <a href="{{ route('usuarios.edit', $user) }}" class="text-muted small" title="Vincular con empleado">
                                <i class="fas fa-link me-1"></i>Vincular
                            </a>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('usuarios.show', $user) }}" class="btn btn-outline-info" title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('usuarios.edit', $user) }}" class="btn btn-outline-warning" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->canBeDeleted())
                                <form method="POST" action="{{ route('usuarios.destroy', $user) }}"
                                    class="d-inline"
                                    onsubmit="return confirm('¿Eliminar este usuario? Esta acción no se puede deshacer.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                        <i class="fas fa-trash"></i>
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
                            <p class="mb-0">No hay usuarios registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginación --}}
    @if($usuarios->hasPages())
    <div class="card-footer">
        {{ $usuarios->links() }}
    </div>
    @endif
</div>
@endsection