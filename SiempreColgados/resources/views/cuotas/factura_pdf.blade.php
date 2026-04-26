<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Factura #{{ $cuota->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #1e40af;
            font-size: 18px;
        }

        .header p {
            margin: 3px 0;
            color: #64748b;
        }

        .info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .info-box {
            width: 48%;
        }

        .info-box h3 {
            margin: 0 0 8px 0;
            font-size: 13px;
            color: #1e40af;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }

        .info-box p {
            margin: 3px 0;
        }

        .factura-info {
            text-align: right;
            margin-bottom: 20px;
        }

        .factura-info p {
            margin: 2px 0;
        }

        .factura-info .numero {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th {
            background: #f1f5f9;
            padding: 8px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #e2e8f0;
        }

        td {
            padding: 8px;
            border: 1px solid #e2e8f0;
        }

        .total {
            text-align: right;
            margin-top: 20px;
            font-size: 14px;
        }

        .total strong {
            font-size: 16px;
            color: #1e40af;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #64748b;
            font-size: 10px;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }

        .pagada {
            background: #dcfce7;
            color: #166534;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 600;
        }

        .pendiente {
            background: #fef3c7;
            color: #92400e;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>{{ $empresa['nombre'] }}</h1>
        <p>CIF: {{ $empresa['cif'] }}</p>
        <p>{{ $empresa['direccion'] }}</p>
        <p>Tel: {{ $empresa['telefono'] }} | Email: {{ $empresa['email'] }}</p>
    </div>

    <div class="factura-info">
        <p class="numero">FACTURA #{{ str_pad($cuota->id, 6, '0', STR_PAD_LEFT) }}</p>
        <p>Fecha de emisión: {{ $cuota->fecha_emision?->format('d/m/Y') }}</p>
        @if($cuota->pagada && $cuota->fecha_pago)
        <p>Fecha de pago: {{ $cuota->fecha_pago?->format('d/m/Y') }}</p>
        @endif
        <p>Estado: <span class="{{ $cuota->pagada ? 'pagada' : 'pendiente' }}">
                {{ $cuota->pagada ? 'PAGADA' : 'PENDIENTE' }}
            </span></p>
    </div>

    <div class="info">
        <div class="info-box">
            <h3>Facturado a</h3>
            <p><strong>{{ $cuota->cliente->nombre }}</strong></p>
            <p>CIF: {{ $cuota->cliente->cif }}</p>
            <p>Tel: {{ $cuota->cliente->telefono }}</p>
            <p>Email: {{ $cuota->cliente->correo }}</p>
            <p>País: {{ strtoupper($cuota->cliente->pais) }}</p>
        </div>
        <div class="info-box">
            <h3>Datos de la cuota</h3>
            <p><strong>Concepto:</strong></p>
            <p>{{ $cuota->concepto }}</p>
            @if($cuota->notas)
            <p><strong>Notas:</strong> {{ $cuota->notas }}</p>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Concepto</th>
                <th style="text-align: right;">Importe</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $cuota->concepto }}</td>
                <td style="text-align: right;">
                    {{ number_format($cuota->importe, 2, ',', '.') }} {{ $cuota->cliente->moneda }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        <p>Total: <strong>{{ number_format($cuota->importe, 2, ',', '.') }} {{ $cuota->cliente->moneda }}</strong></p>
        @if($cuota->cliente->moneda !== 'EUR' && $cuota->importe_euros)
        <p style="font-size: 10px; color: #64748b;">
            Equivalente: {{ number_format($cuota->importe_euros, 2, ',', '.') }} € (tipo de cambio del día de pago)
        </p>
        @endif
    </div>

    <div class="footer">
        <p>Gracias por confiar en {{ $empresa['nombre'] }}</p>
        <p>Factura generada el {{ $fecha_generacion->format('d/m/Y H:i') }} | Documento válido como justificante de pago</p>
    </div>

</body>

</html>