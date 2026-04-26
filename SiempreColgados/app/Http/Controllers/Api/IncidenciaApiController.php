<?php
// app/Http/Controllers/Api/IncidenciaApiController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Incidencia;
use App\Models\Cliente;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Controlador API REST para incidencias (Vue SPA - Problema 3.2)
 * 
 * @author CFGS DWES IES La Marisma
 * @version 1.2
 * @date 2026-04-26
 */
class IncidenciaApiController extends Controller
{
    /**
     * GET /api/incidencias - Lista incidencias con filtros y paginación
     */
    public function index(Request $request): JsonResponse
    {
        $query = Incidencia::with(['cliente', 'operario']);

        // Filtros
        if ($request->filled('buscar')) {
            $query->where(function ($q) use ($request) {
                $q->where('descripcion', 'like', "%{$request->buscar}%")
                    ->orWhereHas('cliente', fn($c) => $c->where('nombre', 'like', "%{$request->buscar}%"));
            });
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('operario')) {
            $query->where('operario_id', $request->operario);
        }

        // Ordenación
        $sort = $request->get('sort', 'created_at');
        $dir = $request->get('direction', 'desc');
        $allowed = ['id', 'cliente_id', 'descripcion', 'operario_id', 'estado', 'created_at'];

        if (!in_array($sort, $allowed)) $sort = 'created_at';
        $dir = strtolower($dir) === 'asc' ? 'asc' : 'desc';

        return response()->json($query->orderBy($sort, $dir)->paginate(20));
    }

    /**
     * GET /api/incidencias/{id} - Detalle de incidencia
     */
    public function show(Incidencia $incidencia): JsonResponse
    {
        return response()->json($incidencia->load(['cliente', 'operario']));
    }

    /**
     * POST /api/incidencias - Crear incidencia
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'descripcion' => 'required|string|min:10|max:1000',
                'correo' => 'required|email',
                'operario_id' => 'nullable|exists:empleados,id',
                'persona_contacto' => 'nullable|string|max:255',
                'telefono_contacto' => 'nullable|string|max:20',
                'estado' => 'nullable|in:P,E,R,C',
                'fecha_realizacion' => 'nullable|date',
                'fichero' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,zip',
            ]);

            // Manejo de archivo con nombre original
            if ($request->hasFile('fichero')) {
                $archivo = $request->file('fichero');
                $nombreOriginal = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $archivo->getClientOriginalExtension();
                $nombreLimpio = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nombreOriginal);
                $nombreUnico = time() . '_' . uniqid() . '_' . $nombreLimpio . '.' . $extension;
                $validated['fichero'] = $archivo->storeAs('incidencias', $nombreUnico, 'public');
            }

            $incidencia = Incidencia::create($validated);
            return response()->json($incidencia->load(['cliente', 'operario']), 201);
        } catch (\Exception $e) {
            Log::error('API Store Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear incidencia'], 500);
        }
    }

    /**
     * PUT /api/incidencias/{id} - Actualizar incidencia
     */
    public function update(Request $request, Incidencia $incidencia): JsonResponse
    {
        try {
            $validated = $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'descripcion' => 'required|string|min:10|max:1000',
                'correo' => 'required|email',
                'operario_id' => 'nullable|exists:empleados,id',
                'estado' => 'nullable|in:P,E,R,C',
                'fecha_realizacion' => 'nullable|date',
                'fichero' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,zip',
            ]);

            // Eliminar archivo anterior si se solicita
            if ($request->input('eliminar_fichero') && $incidencia->fichero) {
                Storage::disk('public')->delete($incidencia->fichero);
                $validated['fichero'] = null;
            }
            // Subir nuevo archivo
            elseif ($request->hasFile('fichero')) {
                if ($incidencia->fichero) Storage::disk('public')->delete($incidencia->fichero);
                $archivo = $request->file('fichero');
                $nombreOriginal = pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $archivo->getClientOriginalExtension();
                $nombreLimpio = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nombreOriginal);
                $nombreUnico = time() . '_' . uniqid() . '_' . $nombreLimpio . '.' . $extension;
                $validated['fichero'] = $archivo->storeAs('incidencias', $nombreUnico, 'public');
            }

            $incidencia->update($validated);
            return response()->json($incidencia->load(['cliente', 'operario']));
        } catch (\Exception $e) {
            Log::error('API Update Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar incidencia'], 500);
        }
    }

    /**
     * DELETE /api/incidencias/{id} - Eliminar incidencia (soft delete)
     */
    public function destroy(Incidencia $incidencia): JsonResponse
    {
        try {
            // Soft delete: marcar como cancelada
            $incidencia->update(['estado' => 'C']);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('API Destroy Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar incidencia'], 500);
        }
    }

    /**
     * GET /api/operarios - Lista operarios para select Vue
     */
    public function operarios(): JsonResponse
    {
        return response()->json(
            Empleado::operarios()->orderBy('nombre')->get(['id', 'nombre'])
        );
    }

    /**
     * GET /api/clientes - Lista clientes para select Vue
     */
    public function clientes(): JsonResponse
    {
        return response()->json(
            Cliente::activos()->orderBy('nombre')->get(['id', 'nombre'])
        );
    }
}
