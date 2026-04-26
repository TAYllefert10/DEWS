<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\FacturaCuotaMail;

class CuotaController extends Controller
{
    /**
     * Listado de cuotas con filtros, ordenación y paginación.
     */
    public function index()
    {
        $query = Cuota::with('cliente');

        // Filtros existentes
        if (request('pagada') !== null) {
            $query->where('pagada', request('pagada') === '1');
        }
        if (request('cliente_id')) {
            $query->where('cliente_id', request('cliente_id'));
        }
        if (request('fecha_desde')) {
            $query->whereDate('fecha_emision', '>=', request('fecha_desde'));
        }
        if (request('fecha_hasta')) {
            $query->whereDate('fecha_emision', '<=', request('fecha_hasta'));
        }

        // Columnas permitidas para ordenar (seguridad)
        $allowedSorts = ['id', 'cliente_id', 'concepto', 'fecha_emision', 'importe', 'pagada', 'importe_euros'];

        // Obtener parámetros de ordenación con valores por defecto
        $sortBy = request('sort', 'fecha_emision'); // Por defecto: más recientes primero
        $sortDir = request('direction', 'desc');    // Por defecto: descendente

        // Validar que la columna sea permitida
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'fecha_emision';
        }

        // Validar dirección de orden
        $sortDir = strtolower($sortDir) === 'desc' ? 'desc' : 'asc';

        // Consulta con ordenación y paginación
        $cuotas = $query->orderBy($sortBy, $sortDir)->paginate(20);

        // Mantener filtros + ordenación en la paginación
        $cuotas->appends([
            'sort' => $sortBy,
            'direction' => $sortDir,
            'cliente_id' => request('cliente_id'),
            'pagada' => request('pagada'),
            'fecha_desde' => request('fecha_desde'),
            'fecha_hasta' => request('fecha_hasta'),
        ]);

        $clientes = Cliente::activos()->orderBy('nombre')->get();

        return view('cuotas.index', compact('cuotas', 'clientes', 'sortBy', 'sortDir'));
    }

    public function create()
    {
        $clientes = Cliente::activos()->orderBy('nombre')->get();
        return view('cuotas.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string|max:255',
            'fecha_emision' => 'required|date',
            'importe' => 'required|numeric|min:0',
            'notas' => 'nullable|string|max:1000',
        ]);

        Cuota::create([
            'cliente_id' => $validated['cliente_id'],
            'concepto' => $validated['concepto'],
            'fecha_emision' => $validated['fecha_emision'],
            'importe' => $validated['importe'],
            'pagada' => false,
            'fecha_pago' => null,
            'importe_euros' => null,
            'notas' => $validated['notas'] ?? null,
            'fichero_factura' => null,
        ]);

        return redirect()->route('cuotas.index')
            ->with('success', 'Cuota creada correctamente.');
    }

    public function show(Cuota $cuota)
    {
        return view('cuotas.show', compact('cuota'));
    }

    public function edit(Cuota $cuota)
    {
        if ($cuota->pagada) {
            return redirect()->route('cuotas.index')
                ->with('error', 'No se puede editar una cuota pagada.');
        }

        $clientes = Cliente::activos()->orderBy('nombre')->get();
        return view('cuotas.edit', compact('cuota', 'clientes'));
    }

    public function update(Request $request, Cuota $cuota)
    {
        if ($cuota->pagada) {
            return redirect()->route('cuotas.index')
                ->with('error', 'No se puede editar una cuota pagada.');
        }

        $validated = $request->validate([
            'concepto' => 'required|string|max:255',
            'fecha_emision' => 'required|date',
            'importe' => 'required|numeric|min:0',
            'notas' => 'nullable|string|max:1000',
        ]);

        $cuota->update($validated);

        return redirect()->route('cuotas.index')
            ->with('success', 'Cuota actualizada correctamente.');
    }

    public function destroy(Cuota $cuota)
    {
        if ($cuota->pagada) {
            return redirect()->route('cuotas.index')
                ->with('error', 'No se puede eliminar una cuota pagada.');
        }

        if ($cuota->fichero_factura && Storage::disk('public')->exists($cuota->fichero_factura)) {
            Storage::disk('public')->delete($cuota->fichero_factura);
        }

        $cuota->delete();

        return redirect()->route('cuotas.index')
            ->with('success', 'Cuota eliminada correctamente.');
    }

    public function generarRemesa(Request $request)
    {
        $fechaEmision = $request->input('fecha_emision', date('Y-m-d'));
        $clientes = Cliente::activos()->get();
        $creadas = 0;

        foreach ($clientes as $cliente) {
            $existe = Cuota::where('cliente_id', $cliente->id)
                ->whereMonth('fecha_emision', date('m', strtotime($fechaEmision)))
                ->whereYear('fecha_emision', date('Y', strtotime($fechaEmision)))
                ->where('concepto', 'like', 'Cuota mensual%')
                ->exists();

            if (!$existe) {
                Cuota::create([
                    'cliente_id' => $cliente->id,
                    'concepto' => 'Cuota mensual - ' . date('F Y', strtotime($fechaEmision)),
                    'fecha_emision' => $fechaEmision,
                    'importe' => $cliente->importe_cuota,
                    'pagada' => false,
                    'fecha_pago' => null,
                    'importe_euros' => null,
                    'notas' => 'Generada automáticamente',
                    'fichero_factura' => null,
                ]);
                $creadas++;
            }
        }

        return redirect()->route('cuotas.index')
            ->with('success', "Remesa generada: {$creadas} cuotas creadas.");
    }

    public function marcarPagada(Cuota $cuota)
    {
        if ($cuota->pagada) {
            return redirect()->route('cuotas.index')
                ->with('error', 'Esta cuota ya está pagada.');
        }

        $importeEuros = $cuota->importe;
        if ($cuota->cliente->moneda !== 'EUR') {
            $importeEuros = $this->convertirAEuros($cuota->importe, $cuota->cliente->moneda);
        }

        $cuota->update([
            'pagada' => true,
            'fecha_pago' => now(),
            'importe_euros' => round($importeEuros, 2),
        ]);

        // Generar PDF
        try {
            $this->generarFacturaPDF($cuota);
        } catch (\Exception $e) {
            \Log::error('Error generando factura: ' . $e->getMessage());
        }

        return redirect()->route('cuotas.index')
            ->with('success', 'Cuota marcada como pagada.');
    }

    /**
     * ✅ VER FACTURA - Mostrar PDF en navegador
     */
    public function verFactura(Cuota $cuota)
    {
        try {
            // Si no existe PDF, generarlo
            if (!$cuota->fichero_factura) {
                $this->generarFacturaPDF($cuota);
                $cuota->refresh();
            }

            // Verificar que existe el archivo
            if (!$cuota->fichero_factura || !Storage::disk('public')->exists($cuota->fichero_factura)) {
                // Generar PDF al vuelo y mostrarlo
                return $this->generarYMostrarPDF($cuota);
            }

            // Obtener ruta absoluta
            $ruta = Storage::disk('public')->path($cuota->fichero_factura);

            // Verificar que el archivo existe físicamente
            if (!file_exists($ruta)) {
                return $this->generarYMostrarPDF($cuota);
            }

            // Retornar PDF inline (se ve en el navegador)
            return response()->file($ruta, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="factura-' . $cuota->id . '.pdf"',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en verFactura: ' . $e->getMessage());
            abort(500, 'Error al mostrar la factura: ' . $e->getMessage());
        }
    }

    /**
     * ✅ DESCARGAR FACTURA - Descargar PDF al PC
     */
    public function descargarFactura(Cuota $cuota)
    {
        try {
            // Si no existe PDF, generarlo
            if (!$cuota->fichero_factura) {
                $this->generarFacturaPDF($cuota);
                $cuota->refresh();
            }

            // Verificar que existe el archivo
            if (!$cuota->fichero_factura || !Storage::disk('public')->exists($cuota->fichero_factura)) {
                // Generar y descargar al vuelo
                return $this->generarYDescargarPDF($cuota);
            }

            // Obtener contenido del archivo
            $contenido = Storage::disk('public')->get($cuota->fichero_factura);

            if (!$contenido) {
                return $this->generarYDescargarPDF($cuota);
            }

            // Nombre del archivo
            $nombre = 'factura-' . $cuota->id . '-' . $cuota->cliente->cif . '.pdf';

            // Retornar respuesta con headers de descarga
            return response($contenido, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $nombre . '"',
                'Content-Length' => strlen($contenido),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en descargarFactura: ' . $e->getMessage());
            abort(500, 'Error al descargar: ' . $e->getMessage());
        }
    }

    /**
     * Generar PDF y mostrarlo en navegador (sin guardar)
     */
    private function generarYMostrarPDF(Cuota $cuota)
    {
        $pdf = $this->crearPDF($cuota);
        return $pdf->stream('factura-' . $cuota->id . '.pdf');
    }

    /**
     * Generar PDF y descargarlo (sin guardar)
     */
    private function generarYDescargarPDF(Cuota $cuota)
    {
        $pdf = $this->crearPDF($cuota);
        $nombre = 'factura-' . $cuota->id . '-' . $cuota->cliente->cif . '.pdf';
        return $pdf->download($nombre);
    }

    /**
     * Crear objeto PDF
     */
    private function crearPDF(Cuota $cuota)
    {
        $data = [
            'cuota' => $cuota,
            'empresa' => [
                'nombre' => 'SiempreColgados S.L.',
                'cif' => 'B12345678',
                'direccion' => 'C/ Ascensores 1, 21001 Huelva',
                'telefono' => '959 123 456',
                'email' => 'facturacion@siemprecolgados.local',
            ],
            'fecha_generacion' => now(),
        ];

        return Pdf::loadView('cuotas.factura_pdf', $data)
            ->setPaper('a4')
            ->setOption('default_font', 'dejavu sans');
    }

    /**
     * Generar factura PDF y guardar en storage
     */
    private function generarFacturaPDF(Cuota $cuota): string
    {
        $data = [
            'cuota' => $cuota,
            'empresa' => [
                'nombre' => 'SiempreColgados S.L.',
                'cif' => 'B12345678',
                'direccion' => 'C/ Ascensores 1, 21001 Huelva',
                'telefono' => '959 123 456',
                'email' => 'facturacion@siemprecolgados.local',
            ],
            'fecha_generacion' => now(),
        ];

        // Crear carpeta si no existe
        if (!Storage::disk('public')->exists('facturas')) {
            Storage::disk('public')->makeDirectory('facturas');
        }

        // Generar PDF
        $pdf = Pdf::loadView('cuotas.factura_pdf', $data)
            ->setPaper('a4')
            ->setOption('default_font', 'dejavu sans');

        // Nombre único
        $nombreArchivo = 'facturas/factura-' . $cuota->id . '-' . now()->format('YmdHis') . '.pdf';

        // Guardar
        Storage::disk('public')->put($nombreArchivo, $pdf->output());

        // Actualizar BD
        $cuota->update(['fichero_factura' => $nombreArchivo]);

        return $nombreArchivo;
    }

    private function convertirAEuros(float $importe, string $moneda): float
    {
        $tasas = [
            'USD' => 0.92,
            'GBP' => 1.17,
            'CHF' => 1.03,
        ];
        $tasa = $tasas[$moneda] ?? 1.0;
        return $importe * $tasa;
    }

    public function enviarFactura(Cuota $cuota, Request $request)
    {
        if (!$cuota->cliente->correo) {
            return redirect()->back()
                ->with('error', 'El cliente no tiene email registrado.');
        }

        if (!$cuota->fichero_factura) {
            $this->generarFacturaPDF($cuota);
            $cuota->refresh();
        }

        try {
            Mail::to($cuota->cliente->correo)
                ->send(new FacturaCuotaMail($cuota));

            return redirect()->route('cuotas.index')
                ->with('success', 'Factura enviada a ' . $cuota->cliente->correo);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al enviar: ' . $e->getMessage());
        }
    }
}
