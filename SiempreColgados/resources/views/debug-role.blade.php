<!DOCTYPE html>
<html>

<head>
    <title>Debug Rol</title>
</head>

<body>
    <h1>Debug de Rol de Usuario</h1>
    <pre>
        User ID: {{ auth()->id() }}
        User Name: {{ auth()->user()->name }}
        User Role (DB): {{ auth()->user()->role ?? 'NULL' }}
        Empleado exists: {{ auth()->user()->empleado ? 'YES' : 'NO' }}
        Empleado tipo: {{ auth()->user()->empleado?->tipo ?? 'NULL' }}
        esAdministrador(): {{ auth()->user()->empleado?->esAdministrador() ? 'TRUE' : 'FALSE' }}
        isAdmin(): {{ auth()->user()->isAdmin() ? 'TRUE' : 'FALSE' }}
        Variable $esAdmin: {{ $esAdmin ?? 'NOT PASSED' }}
    </pre>
    <a href="{{ route('dashboard') }}">Volver al dashboard</a>
</body>

</html>