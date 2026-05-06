@extends('layouts.app')

@section('title', 'Venta ' . $venta->numero_comprobante)

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">{{ $venta->numero_comprobante }}</h5>
        <small class="text-muted">{{ $venta->tipo_comprobante }} — {{ $venta->fecha->format('d/m/Y') }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('ventas.ticket', $venta->id) }}"
           class="btn btn-outline-secondary btn-sm" target="_blank">
            <i class="bi bi-printer me-1"></i> Ticket PDF
        </a>
        @if($venta->estado === 'COMPLETADA')
        <form action="{{ route('ventas.anular', $venta->id) }}" method="POST"
              onsubmit="return confirm('¿Anular esta venta?')">
            @csrf @method('PATCH')
            <button class="btn btn-outline-danger btn-sm">
                <i class="bi bi-x-circle me-1"></i> Anular
            </button>
        </form>
        @endif
        <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>
</div>

<div class="row g-3">

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-2">
                <span class="fw-semibold small">Información de la Venta</span>
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><td class="text-muted">Cliente</td>
                        <td class="fw-medium">{{ $venta->cliente->nombre }}</td></tr>
                    <tr><td class="text-muted">Documento</td>
                        <td>{{ $venta->cliente->tipo_documento }}:
                            {{ $venta->cliente->numero_documento }}</td></tr>
                    <tr><td class="text-muted">Fecha</td>
                        <td>{{ $venta->fecha->format('d/m/Y H:i') }}</td></tr>
                    <tr><td class="text-muted">Comprobante</td>
                        <td class="fw-medium text-primary">{{ $venta->numero_comprobante }}</td></tr>
                    <tr><td class="text-muted">Método Pago</td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info">
                                {{ $venta->metodo_pago }}
                            </span>
                        </td></tr>
                    <tr><td class="text-muted">Estado</td>
                        <td>
                            <span class="badge bg-{{ $venta->estado === 'COMPLETADA' ? 'success':'danger' }}">
                                {{ $venta->estado }}
                            </span>
                        </td></tr>
                    @if($venta->observaciones)
                    <tr><td class="text-muted">Notas</td>
                        <td>{{ $venta->observaciones }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-2">
                <span class="fw-semibold small">Resumen Económico</span>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal:</span>
                    <span>S/ {{ number_format($venta->subtotal, 2) }}</span>
                </div>
                @if($venta->descuento > 0)
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Descuento:</span>
                    <span class="text-danger">- S/ {{ number_format($venta->descuento, 2) }}</span>
                </div>
                @endif
                <hr class="my-2">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-bold">Total:</span>
                    <span class="fw-bold fs-5 text-success">S/ {{ number_format($venta->total, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Monto Pagado:</span>
                    <span>S/ {{ number_format($venta->monto_pagado, 2) }}</span>
                </div>
                @if($venta->vuelto > 0)
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Vuelto:</span>
                    <span class="text-primary">S/ {{ number_format($venta->vuelto, 2) }}</span>
                </div>
                @endif
                <hr class="my-2">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Utilidad:</span>
                    <span class="fw-semibold text-primary">S/ {{ number_format($venta->utilidad, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-2">
                <span class="fw-semibold small">Detalle de Productos</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">P. Compra</th>
                            <th class="text-end">P. Venta</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-end">Utilidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->detalles as $i => $det)
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td class="fw-medium">{{ $det->producto_nombre }}</td>
                            <td class="text-center">{{ $det->cantidad }}</td>
                            <td class="text-end text-muted">S/ {{ number_format($det->precio_compra, 2) }}</td>
                            <td class="text-end">S/ {{ number_format($det->precio_venta, 2) }}</td>
                            <td class="text-end fw-semibold">S/ {{ number_format($det->subtotal, 2) }}</td>
                            <td class="text-end text-primary">S/ {{ number_format($det->utilidad, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="5" class="text-end fw-bold">Totales:</td>
                            <td class="text-end fw-bold text-success">
                                S/ {{ number_format($venta->total, 2) }}
                            </td>
                            <td class="text-end fw-bold text-primary">
                                S/ {{ number_format($venta->utilidad, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <a href="{{ route('reportes.ventas.ticket-pdf', $venta->id) }}"
   class="btn btn-outline-danger btn-sm">
    <i class="bi bi-file-pdf me-1"></i> Descargar PDF
</a>
        </div>
    </div>

</div>

@endsection