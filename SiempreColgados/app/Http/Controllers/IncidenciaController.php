<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Cliente;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IncidenciaController extends Controller
{
    /**
     * Listado de incidencias con filtrado por rol, búsqueda, estado, ordenación y paginación.
     */
    public function index()
    {
        $query = Incidencia::with(['cliente', 'operario']);

        // Si es operario, solo ve sus incidencias asignadas
        if (!Auth::user()->isAdmin() && Auth::user()->empleado) {
            $query->where('operario_id', Auth::user()->empleado->id);
        }

        // Filtros opcionales por estado
        if (request('estado') && in_array(request('estado'), ['P', 'E', 'R', 'C'])) {
            $query->where('estado', request('estado'));
        }

        // Búsqueda por descripción o cliente
        if (request('buscar')) {
            $query->where(function ($q) {
                $q->where('descripcion', 'like', '%' . request('buscar') . '%')
                    ->orWhereHas('cliente', function ($qc) {
                        $qc->where('nombre', 'like', '%' . request('buscar') . '%');
                    });
            });
        }

        // Columnas permitidas para ordenar (seguridad)
        $allowedSorts = ['id', 'cliente_id', 'descripcion', 'operario_id', 'estado', 'created_at', 'fecha_realizacion'];

        // Obtener parámetros de ordenación con valores por defecto
        $sortBy = request('sort', 'created_at'); // Por defecto: más recientes primero
        $sortDir = request('direction', 'desc');    // Por defecto: descendente

        // Validar que la columna sea permitida
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }

        // Validar dirección de orden
        $sortDir = strtolower($sortDir) === 'desc' ? 'desc' : 'asc';

        // Consulta con ordenación y paginación
        $incidencias = $query->orderBy($sortBy, $sortDir)->paginate(20);

        // Mantener filtros + ordenación en la paginación
        $incidencias->appends([
            'sort' => $sortBy,
            'direction' => $sortDir,
            'estado' => request('estado'),
            'buscar' => request('buscar'),
        ]);

        return view('incidencias.index', compact('incidencias', 'sortBy', 'sortDir'));
    }

    /**
     * Formulario para crear nueva incidencia.
     */
    public function create()
    {
        $clientes = Cliente::activos()->orderBy('nombre')->get();
        $operarios = Empleado::operarios()->orderBy('nombre')->get();

        return view('incidencias.create', compact('clientes', 'operarios'));
    }

    /**
     * Guardar nueva incidencia con nombre original de archivo.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'operario_id' => 'nullable|exists:empleados,id',
            'persona_contacto' => 'required|string|max:255',
            'telefono_contacto' => 'nullable|string|max:20',
            'descripcion' => 'required|string|min:10|max:1000',
            'correo' => 'nullable|email',
            'estado' => 'required|in:P,E,R,C',
            'fecha_realizacion' => 'nullable|date|after_or_equal:today',
            'fichero' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,zip',
        ]);

        // Manejo de archivo adjunto con nombre original
        $rutaFichero = null;
        if ($request->hasFile('fichero') && $request->file('fichero')->isValid()) {
            $archivo = $request->file('fichero');

            // Obtener nombre original y extensión
            $nombreOriginal = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $archivo->getClientOriginalExtension();

            // Sanitizar nombre: eliminar caracteres especiales y espacios
            $nombreLimpio = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nombreOriginal);

            // Generar nombre único: timestamp + nombre_original.extension
            $nombreUnico = time() . '_' . uniqid() . '_' . $nombreLimpio . '.' . $extension;

            // Guardar con nombre personalizado en storage/app/public/incidencias/
            $rutaFichero = $archivo->storeAs('incidencias', $nombreUnico, 'public');
        }

        Incidencia::create([
            'cliente_id' => $validated['cliente_id'],
            'operario_id' => $validated['operario_id'] ?? null,
            'persona_contacto' => $validated['persona_contacto'],
            'telefono_contacto' => $validated['telefono_contacto'] ?? null,
            'descripcion' => $validated['descripcion'],
            'correo' => $validated['correo'] ?? null,
            'estado' => $validated['estado'],
            'fecha_realizacion' => $validated['fecha_realizacion'] ?? null,
            'fichero' => $rutaFichero,
        ]);

        return redirect()->route('incidencias.index')
            ->with('success', 'Incidencia creada correctamente.');
    }

    /**
     * Mostrar detalle de una incidencia.
     */
    public function show(Incidencia $incidencia)
    {
        // Verificar permisos: operario solo ve las suyas
        if (!Auth::user()->isAdmin() && $incidencia->operario_id !== Auth::user()->empleado?->id) {
            abort(403, 'No tienes permiso para ver esta incidencia.');
        }

        return view('incidencias.show', compact('incidencia'));
    }

    /**
     * Formulario para editar incidencia.
     */
    public function edit(Incidencia $incidencia)
    {
        // Verificar permisos
        if (!Auth::user()->isAdmin() && $incidencia->operario_id !== Auth::user()->empleado?->id) {
            abort(403, 'No tienes permiso para editar esta incidencia.');
        }

        $clientes = Cliente::activos()->orderBy('nombre')->get();
        $operarios = Empleado::operarios()->orderBy('nombre')->get();

        return view('incidencias.edit', compact('incidencia', 'clientes', 'operarios'));
    }

    /**
     * Actualizar incidencia con nombre original de archivo.
     */
    public function update(Request $request, Incidencia $incidencia)
    {
        // Verificar permisos
        if (!Auth::user()->isAdmin() && $incidencia->operario_id !== Auth::user()->empleado?->id) {
            abort(403, 'No tienes permiso para modificar esta incidencia.');
        }

        // Validación para edición
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'operario_id' => 'nullable|exists:empleados,id',
            'persona_contacto' => 'required|string|max:255',
            'telefono_contacto' => 'nullable|string|max:20',
            'descripcion' => 'required|string|min:10|max:1000',
            'correo' => 'nullable|email',
            'estado' => 'required|in:P,E,R,C',
            'fecha_realizacion' => 'nullable|date',
            'fichero' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,zip',
        ]);

        // Gestionar eliminación de archivo
        if ($request->input('eliminar_fichero') === '1' && $incidencia->fichero) {
            if (Storage::disk('public')->exists($incidencia->fichero)) {
                Storage::disk('public')->delete($incidencia->fichero);
            }
            $validated['fichero'] = null;
        }
        // Gestionar subida de nuevo archivo con nombre original
        elseif ($request->hasFile('fichero') && $request->file('fichero')->isValid()) {
            // Eliminar archivo anterior si existe
            if ($incidencia->fichero && Storage::disk('public')->exists($incidencia->fichero)) {
                Storage::disk('public')->delete($incidencia->fichero);
            }

            $archivo = $request->file('fichero');

            // Obtener nombre original y extensión
            $nombreOriginal = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $archivo->getClientOriginalExtension();

            // Sanitizar nombre
            $nombreLimpio = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nombreOriginal);

            // Generar nombre único
            $nombreUnico = time() . '_' . uniqid() . '_' . $nombreLimpio . '.' . $extension;

            // Guardar con nombre personalizado
            $validated['fichero'] = $archivo->storeAs('incidencias', $nombreUnico, 'public');
        }

        $incidencia->update($validated);

        return redirect()->route('incidencias.index')
            ->with('success', 'Incidencia actualizada correctamente.');
    }

    /**
     * Eliminar incidencia (soft delete: marcar como cancelada).
     */
    public function destroy(Incidencia $incidencia)
    {
        // Verificar permisos
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Solo administradores pueden eliminar incidencias.');
        }

        $incidencia->update(['estado' => 'C']);

        return redirect()->route('incidencias.index')
            ->with('success', 'Incidencia cancelada correctamente.');
    }

    /**
     * Descargar fichero adjunto de una incidencia.
     */
    public function descargarFichero(Incidencia $incidencia)
    {
        // Verificar permisos
        if (!Auth::user()->isAdmin() && $incidencia->operario_id !== Auth::user()->empleado?->id) {
            abort(403, 'No tienes permiso para descargar este archivo.');
        }

        if (!$incidencia->fichero || !Storage::disk('public')->exists($incidencia->fichero)) {
            abort(404, 'El archivo no existe o fue eliminado.');
        }

        return Storage::disk('public')->download(
            $incidencia->fichero,
            'incidencia-' . $incidencia->id . '-' . basename($incidencia->fichero)
        );
    }
}
