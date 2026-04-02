<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Factura {{ $referencia_compra }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; line-height: 1.5; }
        .header { margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        .company { float: left; width: 50%; }
        .invoice-meta { float: right; width: 50%; text-align: right; }
        .clear { clear: both; }
        .items { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items th { background-color: #2563eb; color: white; padding: 10px; text-align: left; }
        .items td { border: 1px solid #ddd; padding: 8px; vertical-align: middle; }
        .text-right { text-align: right; }
        .total-row { background-color: #f8fafc; font-weight: bold; }
        .footer-info { margin-top: 30px; padding: 15px; background-color: #f3f4f6; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">
            <h2 style="color: #2563eb; margin: 0;">MIC Tecnología</h2>
            <div>Bucaramanga, Colombia</div>
            <div>Email: info@mic.com</div>
        </div>
        <div class="invoice-meta">
            <h3 style="margin: 0;">FACTURA DE VENTA</h3>
            <div><strong>Referencia:</strong> {{ $referencia_compra }}</div>
            <div><strong>Fecha:</strong> {{ $fecha }}</div>
            <div><strong>Cliente:</strong> {{ $user->name }}</div>
        </div>
        <div class="clear"></div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Producto</th>
                <th style="width: 80px;">Cant.</th>
                <th style="width: 120px;">Unitario</th>
                <th style="width: 120px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>
                        <strong>{{ $item['nombre'] }}</strong>
                    </td>
                    <td class="text-right">{{ $item['cantidad'] }}</td>
                    <td class="text-right">$ {{ number_format($item['precio'], 0, ',', '.') }}</td>
                    <td class="text-right">$ {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">TOTAL A PAGAR</td>
                <td class="text-right" style="color: #2563eb; font-size: 14px;">
                    $ {{ number_format($total, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer-info">
        <strong>Detalles del Pago:</strong><br>
        • Método: {{ ucfirst($metodo_pago) }}<br>
        • Estado: Completado<br>
        @if(isset($descripcion) && $descripcion)
            • Notas: {{ $descripcion }}
        @endif
    </div>

    <div style="margin-top: 50px; text-align: center; color: #9ca3af;">
        ¡Gracias por comprar en MIC Tecnología!
    </div>
</body>
</html>