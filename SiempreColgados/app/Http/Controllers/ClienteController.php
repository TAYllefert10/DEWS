<?php
// =============================================================================
// app/Http/Controllers/ClienteController.php
// =============================================================================
namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

/**
 * Controlador CRUD de clientes (solo administradores).
 *
 * @author Alumno DAW
 * @version 1.1
 * @date 2026-04-26
 */
class ClienteController extends Controller
{
    /** @return \Illuminate\View\View */
    public function index(): \Illuminate\View\View
    {
        // Columnas permitidas para ordenar (seguridad)
        $allowedSorts = ['cif', 'nombre', 'telefono', 'correo', 'pais', 'importe_cuota', 'activo'];

        // Obtener parámetros de ordenación con valores por defecto
        $sortBy = request('sort', 'nombre');
        $sortDir = request('direction', 'asc');

        // Validar que la columna sea permitida
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'nombre';
        }

        // Validar dirección de orden
        $sortDir = strtolower($sortDir) === 'desc' ? 'desc' : 'asc';

        // Consulta con ordenación y paginación
        $clientes = Cliente::orderBy($sortBy, $sortDir)->paginate(20);

        // Mantener parámetros en la paginación
        $clientes->appends(['sort' => $sortBy, 'direction' => $sortDir]);

        // ✅ Pasar variables a la vista
        return view('clientes.index', compact('clientes', 'sortBy', 'sortDir'));
    }

    /** @return \Illuminate\View\View */
    public function create(): \Illuminate\View\View
    {
        return view('clientes.create');
    }

    /**
     * Almacena un nuevo cliente.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'cif'              => 'required|string|max:9|unique:clientes,cif',
            'nombre'           => 'required|string|max:255',
            'telefono'         => 'nullable|string|max:20',
            'correo'           => 'nullable|email',
            'cuenta_corriente' => 'nullable|string|max:34',
            'pais'             => 'required|string|size:2',
            'moneda'           => 'required|string|size:3',
            'importe_cuota'    => 'required|numeric|min:0',
        ]);

        Cliente::create($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    /** @param Cliente $cliente @return \Illuminate\View\View */
    public function edit(Cliente $cliente): \Illuminate\View\View
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Actualiza datos del cliente.
     *
     * @param Request $request
     * @param Cliente $cliente
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Cliente $cliente): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'nombre'           => 'required|string|max:255',
            'telefono'         => 'nullable|string|max:20',
            'correo'           => 'nullable|email',
            'cuenta_corriente' => 'nullable|string|max:34',
            'pais'             => 'required|string|size:2',
            'moneda'           => 'required|string|size:3',
            'importe_cuota'    => 'required|numeric|min:0',
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Da de baja al cliente (soft delete: activo = false).
     *
     * @param Cliente $cliente
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Cliente $cliente): \Illuminate\Http\RedirectResponse
    {
        $cliente->update(['activo' => false]);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente dado de baja correctamente.');
    }

    /**
     * ✅ NUEVO: Dar de alta al cliente (reactivar: activo = true).
     *
     * @param Cliente $cliente
     * @return \Illuminate\Http\RedirectResponse
     */
    public function darAlta(Cliente $cliente): \Illuminate\Http\RedirectResponse
    {
        $cliente->update(['activo' => true]);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente dado de alta correctamente.');
    }
}
