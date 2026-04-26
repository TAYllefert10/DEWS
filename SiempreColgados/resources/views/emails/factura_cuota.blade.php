<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Factura de Cuota</title>
</head>

<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; color: #333;">

    <div style="background: #1e40af; color: white; padding: 20px; border-radius: 10px 10px 0 0; text-align: center;">
        <h1 style="margin: 0; font-size: 20px;">SiempreColgados S.L.</h1>
        <p style="margin: 5px 0 0; opacity: 0.9;">Factura de Cuota</p>
    </div>

    <div style="background: #f8fafc; padding: 20px; border: 1px solid #e2e8f0; border-top: none;">

        <p>Hola <strong>{{ $cliente->nombre }}</strong>,</p>

        <p>Adjuntamos la factura correspondiente a la cuota de mantenimiento de su ascensor:</p>

        <table style="width: 100%; border-collapse: collapse; margin: 15px 0;">
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;"><strong>Concepto:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">{{ $cuota->concepto }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;"><strong>Fecha:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;">{{ $cuota->fecha_emision?->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #e2e8f0;"><strong>Importe:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #e2e8f0; font-weight: bold;">
                    {{ number_format($cuota->importe, 2, ',', '.') }} {{ $cliente->moneda }}
                </td>
            </tr>
            @if($cuota->pagada)
            <tr>
                <td style="padding: 8px;"><strong>Estado:</strong></td>
                <td style="padding: 8px; color: #22c55e; font-weight: bold;">✅ PAGADA</td>
            </tr>
            @endif
        </table>

        <p style="margin: 20px 0;">Puede encontrar la factura adjunta en formato PDF. Si tiene alguna duda, no dude en contactarnos.</p>

        <p style="margin: 20px 0 0;">
            Atentamente,<br>
            <strong>Departamento de Administración</strong><br>
            SiempreColgados S.L.<br>
            📧 {{ config('mail.from.address') }}<br>
            📞 {{ config('app.phone', '959 123 456') }}
        </p>

    </div>

    <div style="background: #f1f5f9; padding: 15px; border-radius: 0 0 10px 10px; text-align: center; font-size: 12px; color: #64748b;">
        <p style="margin: 0;">Este es un mensaje automático, por favor no responda a este correo.</p>
    </div>

</body>

</html>