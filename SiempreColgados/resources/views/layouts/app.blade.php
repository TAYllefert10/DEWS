<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inicio') — SiempreColgados</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #1e40af;
            --primary-dark: #1e3a8a;
            --primary-light: #3b82f6;
            --accent: #f59e0b;
            --sidebar-w: 260px;
            --topbar-h: 60px;
            --bg: #f1f5f9;
            --surface: #ffffff;
            --border: #e2e8f0;
            --text: #0f172a;
            --text-muted: #64748b;
            --danger: #ef4444;
            --success: #22c55e;
            --warning: #f59e0b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(160deg, #0f2460 0%, #1e40af 60%, #2563eb 100%);
            display: flex;
            flex-direction: column;
            z-index: 200;
            box-shadow: 4px 0 24px rgba(30, 64, 175, .25);
            overflow: hidden;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
            pointer-events: none;
        }

        .sidebar-brand {
            padding: 24px 20px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }

        .sidebar-brand .brand-icon {
            width: 42px;
            height: 42px;
            background: var(--accent);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fff;
            margin-bottom: 10px;
            box-shadow: 0 4px 12px rgba(245, 158, 11, .4);
        }

        .sidebar-brand h1 {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            margin: 0;
            letter-spacing: -.3px;
        }

        .sidebar-brand p {
            font-size: 11px;
            color: rgba(255, 255, 255, .5);
            margin: 2px 0 0;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 16px 12px;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .2);
            border-radius: 4px;
        }

        .nav-section {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            color: rgba(255, 255, 255, .35);
            text-transform: uppercase;
            padding: 12px 8px 6px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            color: rgba(255, 255, 255, .72);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all .18s;
            margin-bottom: 2px;
        }

        .nav-link i {
            width: 18px;
            text-align: center;
            font-size: 15px;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, .12);
            color: #fff;
        }

        .nav-link.active {
            background: rgba(255, 255, 255, .18);
            color: #fff;
            box-shadow: inset 3px 0 0 var(--accent);
        }

        .nav-link .badge-count {
            margin-left: auto;
            background: var(--accent);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255, 255, 255, .1);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(255, 255, 255, .08);
            margin-bottom: 8px;
        }

        .sidebar-user .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar-user .user-info {
            min-width: 0;
        }

        .sidebar-user .user-name {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user .user-role {
            font-size: 11px;
            color: rgba(255, 255, 255, .5);
        }

        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            z-index: 100;
            gap: 16px;
        }

        .topbar-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-btn {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
            font-size: 15px;
        }

        .topbar-btn:hover {
            background: var(--bg);
            color: var(--primary);
        }

        .main-content {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
        }

        .page-body {
            padding: 28px 28px;
        }

        .card {
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .04);
        }

        .card-header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            border-radius: 14px 14px 0 0 !important;
            padding: 16px 20px;
            font-weight: 600;
            font-size: 14px;
        }

        .stat-card {
            border-radius: 14px;
            padding: 22px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            border: 1px solid var(--border);
            background: var(--surface);
            transition: transform .2s, box-shadow .2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-muted);
        }

        .badge-estado-P {
            background: #fef3c7;
            color: #92400e;
            font-weight: 600;
        }

        .badge-estado-R {
            background: #dcfce7;
            color: #166534;
            font-weight: 600;
        }

        .badge-estado-C {
            background: #fee2e2;
            color: #991b1b;
            font-weight: 600;
        }

        .table thead th {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--text-muted);
            border-bottom: 2px solid var(--border);
            padding: 12px 16px;
            background: #f8fafc;
        }

        .table td {
            padding: 13px 16px;
            vertical-align: middle;
            font-size: 14px;
        }

        .table tbody tr {
            transition: background .12s;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .alert {
            border-radius: 10px;
            font-size: 14px;
            border: none;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border-left: 4px solid var(--success);
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }

        .alert-warning {
            background: #fffbeb;
            color: #92400e;
            border-left: 4px solid var(--warning);
        }

        .btn {
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 16px;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .form-control,
        .form-select {
            border-radius: 9px;
            border: 1.5px solid var(--border);
            font-size: 14px;
            padding: 9px 13px;
            transition: border .15s, box-shadow .15s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 64, 175, .12);
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
        }

        code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            background: #f1f5f9;
            padding: 2px 6px;
            border-radius: 5px;
            color: var(--primary);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform .25s;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                left: 0;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    @auth
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="fas fa-elevator"></i></div>
            <h1>SiempreColgados</h1>
            <p>Panel de Gestión</p>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section">General</div>

            <a href="{{ url('/dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fas fa-gauge-high"></i> Dashboard
            </a>

            <a href="{{ url('/incidencias') }}" class="nav-link {{ request()->is('incidencias*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i> Incidencias
                @php
                $pend = \App\Models\Incidencia::where('estado','P')
                ->when(!auth()->user()->esAdministrador(), fn($q) => $q->where('operario_id', auth()->id()))
                ->count();
                @endphp
                @if($pend > 0)<span class="badge-count">{{ $pend }}</span>@endif
            </a>

            {{-- ✅ SECCIÓN ADMINISTRACIÓN (SOLO ADMIN) --}}
            @can('admin')
            <div class="nav-section">Administración</div>

            <a href="{{ url('/clientes') }}" class="nav-link {{ request()->is('clientes*') ? 'active' : '' }}">
                <i class="fas fa-building"></i> Clientes
            </a>

            <a href="{{ url('/empleados') }}" class="nav-link {{ request()->is('empleados*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Empleados
            </a>

            <a href="{{ url('/cuotas') }}" class="nav-link {{ request()->is('cuotas*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar"></i> Cuotas
                @php $pCuotas = \App\Models\Cuota::where('pagada',false)->count() @endphp
                @if($pCuotas > 0)<span class="badge-count">{{ $pCuotas }}</span>@endif
            </a>
            @endcan

            {{-- ✅ ELIMINADO: Sección Extras y enlace Portal Cliente --}}
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="avatar">{{ strtoupper(substr(Auth::user()->nombre,0,1)) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->nombre }}</div>
                    <div class="user-role">
                        {{ ucfirst(Auth::user()->tipo) }}
                        @if(Auth::user()->google_id)
                        <i class="fab fa-google text-success ms-1" title="Login con Google"></i>
                        @endif
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ url('/logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm w-100" style="background:rgba(255,255,255,.1); color:rgba(255,255,255,.7); border:none;">
                    <i class="fas fa-right-from-bracket me-1"></i> Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <div class="topbar">
        <button class="topbar-btn d-md-none" onclick="document.getElementById('sidebar').classList.toggle('open')">
            <i class="fas fa-bars"></i>
        </button>
        <span class="topbar-title">@yield('title', 'Dashboard')</span>
        <div class="topbar-right">
            <a href="{{ url('/incidencias/create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Nueva incidencia
            </a>
            <span class="topbar-btn" title="{{ now()->format('d/m/Y H:i') }}">
                <i class="fas fa-clock"></i>
            </span>
        </div>
    </div>

    <div class="main-content">
        <div class="page-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-circle-exclamation me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @yield('content')
        </div>
    </div>
    @else
    @yield('content')
    @endauth

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    @stack('scripts')
</body>

</html>