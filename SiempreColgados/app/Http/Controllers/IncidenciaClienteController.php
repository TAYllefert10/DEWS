<?php
// =============================================================================
// app/Http/Controllers/IncidenciaClienteController.php
// =============================================================================
namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Cliente;
use Illuminate\Http\Request;

/**
 * Controlador para el formulario público de registro de incidencias.
 * Los clientes no necesitan estar autenticados.
 * Se verifica su identidad mediante CIF + teléfono.
 *
 * @author Alumno DAW
 * @version 1.0
 * @date 2024-01-01
 */
class IncidenciaClienteController extends Controller
{
    /**
     * Muestra el formulario público de registro de incidencia.
     *
     * @return \Illuminate\View\View
     */
    public function showForm(): \Illuminate\View\View
    {
        $provincias = Incidencia::$PROVINCIAS;
        return view('incidencias.cliente_form', compact('provincias'));
    }

    /**
     * Procesa el registro. Valida identidad por CIF + teléfono antes de guardar.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'cif'                    => 'required|string|max:9',
            'telefono_cliente'       => 'required|string|max:20',
            'persona_contacto'       => 'required|string|max:150',
            'telefono_contacto'      => ['required', 'regex:/^[0-9\s\-\+\(\)\.]{7,20}$/'],
            'descripcion'            => 'required|string|max:500',
            'correo'                 => 'required|email',
            'direccion'              => 'nullable|string|max:200',
            'poblacion'              => 'nullable|string|max:100',
            'codigo_postal'          => ['nullable', 'regex:/^[0-9]{5}$/'],
            'provincia_codigo'       => 'nullable|in:' . implode(',', array_keys(Incidencia::$PROVINCIAS)),
            'anotaciones_anteriores' => 'nullable|string',
        ]);

        // Verificar identidad del cliente
        $cliente = Cliente::verificarIdentidad($validated['cif'], $validated['telefono_cliente']);

        if (!$cliente) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['cif' => 'No se encontró ningún cliente activo con ese CIF y teléfono. Compruebe los datos.']);
        }

        // Crear incidencia sin operario (lo asignará un administrador)
        Incidencia::create([
            'cliente_id'             => $cliente->id,
            'operario_id'            => null,
            'persona_contacto'       => $validated['persona_contacto'],
            'telefono_contacto'      => $validated['telefono_contacto'],
            'descripcion'            => $validated['descripcion'],
            'correo'                 => $validated['correo'],
            'direccion'              => $validated['direccion'] ?? null,
            'poblacion'              => $validated['poblacion'] ?? null,
            'codigo_postal'          => $validated['codigo_postal'] ?? null,
            'provincia_codigo'       => $validated['provincia_codigo'] ?? null,
            'anotaciones_anteriores' => $validated['anotaciones_anteriores'] ?? null,
            'estado'                 => 'P',
        ]);

        return redirect()->route('incidencia.cliente.form')
            ->with('success', 'Incidencia registrada correctamente. Nos pondremos en contacto con usted en breve.');
    }
}
