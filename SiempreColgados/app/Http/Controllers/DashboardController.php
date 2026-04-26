<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Incidencia;
use App\Models\Cuota;

class DashboardController extends Controller
{
    /**
     * Mostrar el dashboard según el rol del empleado.
     */
    public function index(): View
    {
        // ✅ $user YA ES un Empleado (no tiene relación 'empleado')
        $user = Auth::user();

        // ✅ Calcular si es admin directamente desde el modelo Empleado
        $esAdmin = $user->esAdministrador();

        // ✅ Datos comunes para todos
        $stats = [
            'total_incidencias' => Incidencia::count(),
            'pendientes' => Incidencia::where('estado', 'P')->count(),
            'en_proceso' => Incidencia::where('estado', 'E')->count(),
            'realizadas' => Incidencia::where('estado', 'R')->count(),
        ];

        // ✅ Si es operario, filtrar por sus incidencias asignadas
        if (!$esAdmin && $user->esOperario()) {
            $stats['mis_incidencias'] = Incidencia::where('operario_id', $user->id)->count();
            $stats['mis_pendientes'] = Incidencia::where('operario_id', $user->id)
                ->where('estado', 'P')
                ->count();
        }

        // ✅ Cuotas recientes (solo para admin)
        $cuotas_recientes = $esAdmin
            ? Cuota::with('cliente')->latest()->take(5)->get()
            : collect();

        return view('dashboard', compact('user', 'esAdmin', 'stats', 'cuotas_recientes'));
    }
}
