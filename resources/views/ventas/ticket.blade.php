<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket {{ $venta->numero_comprobante }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .ticket {
            background: #fff;
            width: 300px;
            padding: 16px 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,.15);
        }

        .ticket-header { text-align: center; margin-bottom: 10px; }
        .ticket-header h2 { font-size: 16px; font-weight: bold; }
        .ticket-header p  { font-size: 11px; color: #555; }

        .divider {
            border: none;
            border-top: 1px dashed #999;
            margin: 8px 0;
        }

        .comprobante {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin: 6px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 11px;
        }

        .info-row .label { color: #666; }

        table {
            width: 100%;
            margin: 6px 0;
            font-size: 11px;
        }

        table th {
            font-size: 10px;
            text-transform: uppercase;
            color: #888;
            padding-bottom: 4px;
        }

        table td { padding: 2px 0; }
        table .nombre { max-width: 130px; }

        .totales { margin-top: 6px; }
        .totales .fila {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 2px;
        }
        .totales .total-final {
            font-size: 15px;
            font-weight: bold;
            border-top: 1px dashed #999;
            padding-top: 4px;
            margin-top: 4px;
        }

        .footer {
            text-align: center;
            margin-top: 12px;
            font-size: 10px;
            color: #888;
        }

        .estado-anulado {
            text-align: center;
            color: red;
            font-weight: bold;
            font-size: 14px;
            border: 2px solid red;
            padding: 4px;
            margin-bottom: 8px;
        }

        .btn-imprimir {
            display: block;
            margin: 16px auto 0;
            padding: 8px 24px;
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }

        @media print {
            body { background: white; padding: 0; }
            .ticket { box-shadow: none; }
            .btn-imprimir { display: none; }
        }
    </style>
</head>
<body>

<div>
    <div class="ticket">

        @if($venta->estado === 'ANULADA')
        <div class="estado-anulado">*** ANULADO ***</div>
        @endif

        {{-- ENCABEZADO --}}
        <div class="ticket-header">
            <h2>TIENDA ATIQ</h2>
            <p>Sistema de Ventas</p>
            <p>Huaraz, Ancash - Perú</p>
        </div>

        <hr class="divider">

        <div class="comprobante">{{ $venta->tipo_comprobante }}</div>
        <div style="text-align:center; font-size:13px; font-weight:bold; margin-bottom:6px">
            {{ $venta->numero_comprobante }}
        </div>

        <hr class="divider">

        {{-- INFO VENTA --}}
        <div class="info-row">
            <span class="label">Fecha:</span>
            <span>{{ $venta->fecha->format('d/m/Y') }}
                {{ $venta->created_at->format('H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="label">Cliente:</span>
            <span>{{ $venta->cliente->nombre }}</span>
        </div>
        @if($venta->cliente->numero_documento !== '00000000')
        <div class="info-row">
            <span class="label">{{ $venta->cliente->tipo_documento }}:</span>
            <span>{{ $venta->cliente->numero_documento }}</span>
        </div>
        @endif

        <hr class="divider">

        {{-- PRODUCTOS --}}
        <table>
            <thead>
                <tr>
                    <th class="nombre">Producto</th>
                    <th style="text-align:center">Cant</th>
                    <th style="text-align:right">P.U.</th>
                    <th style="text-align:right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $det)
                <tr>
                    <td class="nombre">{{ Str::limit($det->producto_nombre, 18) }}</td>
                    <td style="text-align:center">{{ $det->cantidad }}</td>
                    <td style="text-align:right">{{ number_format($det->precio_venta, 2) }}</td>
                    <td style="text-align:right">{{ number_format($det->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <hr class="divider">

        {{-- TOTALES --}}
        <div class="totales">
            @if($venta->descuento > 0)
            <div class="fila">
                <span>Subtotal:</span>
                <span>S/ {{ number_format($venta->subtotal + $venta->descuento, 2) }}</span>
            </div>
            <div class="fila">
                <span>Descuento:</span>
                <span>- S/ {{ number_format($venta->descuento, 2) }}</span>
            </div>
            @endif
            <div class="fila total-final">
                <span>TOTAL:</span>
                <span>S/ {{ number_format($venta->total, 2) }}</span>
            </div>
            <div class="fila" style="margin-top:4px">
                <span>Pagó ({{ $venta->metodo_pago }}):</span>
                <span>S/ {{ number_format($venta->monto_pagado, 2) }}</span>
            </div>
            @if($venta->vuelto > 0)
            <div class="fila">
                <span>Vuelto:</span>
                <span>S/ {{ number_format($venta->vuelto, 2) }}</span>
            </div>
            @endif
        </div>

        <hr class="divider">

        {{-- FOOTER --}}
        <div class="footer">
            <p>¡Gracias por su compra!</p>
            <p>Conserve su comprobante</p>
            <p style="margin-top:4px">{{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

    </div>

    <button class="btn-imprimir" onclick="window.print()">
        🖨️ Imprimir Ticket
    </button>
</div>

</body>
</html>