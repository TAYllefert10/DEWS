<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EmpleadoController extends Controller
{
    /**
     * Listado de empleados con filtros y ordenación.
     */
    public function index(Request $request): View
    {
        // ✅ Eliminar con('user') - Empleado YA ES el usuario
        $query = Empleado::query();

        // Filtros opcionales
        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->buscar}%")
                    ->orWhere('dni', 'like', "%{$request->buscar}%")
                    ->orWhere('email', 'like', "%{$request->buscar}%");
            });
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('activo') && in_array($request->activo, ['0', '1'])) {
            $query->where('activo', (bool) $request->activo);
        }

        // Ordenación
        $sortBy = $request->get('sort', 'nombre');
        $sortDir = $request->get('direction', 'asc');
        $allowedSorts = ['id', 'dni', 'nombre', 'email', 'tipo', 'fecha_alta', 'activo', 'created_at'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'nombre';
        }
        $sortDir = strtolower($sortDir) === 'desc' ? 'desc' : 'asc';

        // ✅ Consulta SIN with('user')
        $empleados = $query
            ->orderBy($sortBy, $sortDir)
            ->paginate(20)
            ->withQueryString();

        return view('empleados.index', compact('empleados', 'sortBy', 'sortDir'));
    }

    /**
     * Formulario para crear empleado.
     */
    public function create(): View
    {
        return view('empleados.create');
    }

    /**
     * Guardar nuevo empleado.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'dni' => 'required|string|size:9|unique:empleados,dni',
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:empleados,email',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_alta' => 'required|date|before_or_equal:today',
            'tipo' => 'required|in:operario,administrador',
            'activo' => 'nullable|boolean',
        ], [
            'dni.required' => 'El DNI es obligatorio',
            'dni.size' => 'El DNI debe tener exactamente 9 caracteres',
            'dni.unique' => 'Este DNI ya está registrado',
            'email.required' => 'El email es obligatorio',
            'email.unique' => 'Este email ya está registrado',
            'fecha_alta.before_or_equal' => 'La fecha de alta no puede ser futura',
        ]);

        try {
            Empleado::create([
                ...$validated,
                'activo' => $request->boolean('activo'),
                // ✅ Si se crea con login tradicional, generar password
                'password' => $request->filled('password')
                    ? bcrypt($request->password)
                    : bcrypt(str()->random(24)),
            ]);

            return redirect()->route('empleados.index')
                ->with('success', 'Empleado creado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear empleado: ' . $e->getMessage());
            return back()->withInput()->with('error', 'No se pudo crear el empleado.');
        }
    }

    /**
     * Mostrar detalle de empleado.
     */
    public function show(Empleado $empleado): View
    {
        return view('empleados.show', compact('empleado'));
    }

    /**
     * Formulario para editar empleado.
     */
    public function edit(Empleado $empleado): View
    {
        return view('empleados.edit', compact('empleado'));
    }

    /**
     * Actualizar empleado.
     */
    public function update(Request $request, Empleado $empleado): RedirectResponse
    {
        $validated = $request->validate([
            'dni' => 'required|string|size:9|unique:empleados,dni,' . $empleado->id,
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:empleados,email,' . $empleado->id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_alta' => 'required|date|before_or_equal:today',
            'tipo' => 'required|in:operario,administrador',
            'activo' => 'nullable|boolean',
        ]);

        try {
            $empleado->update([
                ...$validated,
                'activo' => $request->boolean('activo'),
            ]);

            return redirect()->route('empleados.show', $empleado)
                ->with('success', 'Empleado actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar empleado: ' . $e->getMessage());
            return back()->withInput()->with('error', 'No se pudo actualizar el empleado.');
        }
    }

    /**
     * Eliminar empleado (dar de baja lógica).
     */
    public function destroy(Empleado $empleado): RedirectResponse
    {
        // Seguridad: no eliminar el propio empleado
        if ($empleado->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        // Seguridad: no eliminar si es el último administrador activo
        if ($empleado->esAdministrador() && Empleado::where('tipo', 'administrador')->where('activo', true)->count() <= 1) {
            return back()->with('error', 'Debe haber al menos un administrador activo en el sistema.');
        }

        try {
            // ✅ Baja lógica: desactivar en lugar de borrar
            $empleado->update(['activo' => false]);

            return redirect()->route('empleados.index')
                ->with('success', 'Empleado dado de baja correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar empleado: ' . $e->getMessage());
            return back()->with('error', 'No se pudo dar de baja al empleado.');
        }
    }

    /**
     * Dar de alta a empleado (reactivar).
     */
    public function darAlta(Empleado $empleado): RedirectResponse
    {
        try {
            $empleado->update(['activo' => true]);
            return back()->with('success', 'Empleado dado de alta correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al dar de alta: ' . $e->getMessage());
            return back()->with('error', 'No se pudo dar de alta al empleado.');
        }
    }
}
