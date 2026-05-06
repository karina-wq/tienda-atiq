<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            width: 220px;
        }
        .center  { text-align: center; }
        .bold    { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        .row     { display: flex; justify-content: space-between; margin-bottom: 2px; }
        table    { width: 100%; font-size: 9px; margin: 4px 0; }
        table th { font-size: 8px; text-align: left; padding-bottom: 2px; }
        td.r     { text-align: right; }
        td.c     { text-align: center; }
        .total   { font-size: 13px; font-weight: bold; }
        .anulado { color: red; font-weight: bold; text-align: center;
                   border: 1px solid red; padding: 2px; margin-bottom: 4px; }
    </style>
</head>
<body>

@if($venta->estado === 'ANULADA')
<div class="anulado">*** ANULADO ***</div>
@endif

<div class="center bold" style="font-size:14px">TIENDA ATIQ</div>
<div class="center" style="font-size:9px">Huaraz, Ancash - Perú</div>

<div class="divider"></div>

<div class="center bold" style="font-size:11px">{{ $venta->tipo_comprobante }}</div>
<div class="center bold">{{ $venta->numero_comprobante }}</div>

<div class="divider"></div>

<div class="row"><span>Fecha:</span><span>{{ $venta->fecha->format('d/m/Y') }}</span></div>
<div class="row"><span>Cliente:</span><span>{{ Str::limit($venta->cliente->nombre, 18) }}</span></div>
@if($venta->cliente->numero_documento !== '00000000')
<div class="row">
    <span>{{ $venta->cliente->tipo_documento }}:</span>
    <span>{{ $venta->cliente->numero_documento }}</span>
</div>
@endif

<div class="divider"></div>

<table>
    <thead>
        <tr>
            <th style="width:45%">Producto</th>
            <th class="c">Cant</th>
            <th class="r">P.U.</th>
            <th class="r">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venta->detalles as $det)
        <tr>
            <td>{{ Str::limit($det->producto_nombre, 16) }}</td>
            <td class="c">{{ $det->cantidad }}</td>
            <td class="r">{{ number_format($det->precio_venta, 2) }}</td>
            <td class="r">{{ number_format($det->subtotal, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="divider"></div>

@if($venta->descuento > 0)
<div class="row"><span>Subtotal:</span><span>S/ {{ number_format($venta->subtotal + $venta->descuento, 2) }}</span></div>
<div class="row"><span>Descuento:</span><span>-S/ {{ number_format($venta->descuento, 2) }}</span></div>
@endif

<div class="row total"><span>TOTAL:</span><span>S/ {{ number_format($venta->total, 2) }}</span></div>
<div class="row"><span>Pagó ({{ $venta->metodo_pago }}):</span>
    <span>S/ {{ number_format($venta->monto_pagado, 2) }}</span></div>
@if($venta->vuelto > 0)
<div class="row"><span>Vuelto:</span><span>S/ {{ number_format($venta->vuelto, 2) }}</span></div>
@endif

<div class="divider"></div>

<div class="center">¡Gracias por su compra!</div>
<div class="center" style="font-size:8px">{{ now()->format('d/m/Y H:i:s') }}</div>

</body>
</html>