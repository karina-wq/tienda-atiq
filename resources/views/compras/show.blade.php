@extends('layouts.app')

@section('title', 'Detalle Compra #' . $compra->id)

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Compra #{{ $compra->id }}</h5>
        <small class="text-muted">{{ $compra->tipo_comprobante }} — {{ $compra->numero_comprobante ?? 'Sin número' }}</small>
    </div>
    <div class="d-flex gap-2">
        @if($compra->estado === 'COMPLETADA')
        <form action="{{ route('compras.anular', $compra->id) }}" method="POST"
              onsubmit="return confirm('¿Anular esta compra? El stock se revertirá.')">
            @csrf @method('PATCH')
            <button class="btn btn-outline-danger btn-sm">
                <i class="bi bi-x-circle me-1"></i> Anular
            </button>
        </form>
        @endif
        <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>
</div>

<div class="row g-3">

    {{-- INFO --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-2">
                <span class="fw-semibold small">Información de la Compra</span>
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><td class="text-muted">Proveedor</td>
                        <td class="fw-medium">{{ $compra->proveedor->razon_social }}</td></tr>
                    <tr><td class="text-muted">Fecha</td>
                        <td>{{ $compra->fecha->format('d/m/Y') }}</td></tr>
                    <tr><td class="text-muted">Comprobante</td>
                        <td>{{ $compra->tipo_comprobante }} {{ $compra->numero_comprobante }}</td></tr>
                    <tr><td class="text-muted">Estado</td>
                        <td>
                            <span class="badge bg-{{ $compra->estado === 'COMPLETADA' ? 'success':'danger' }}">
                                {{ $compra->estado }}
                            </span>
                        </td>
                    </tr>
                    @if($compra->observaciones)
                    <tr><td class="text-muted">Observaciones</td>
                        <td>{{ $compra->observaciones }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- TOTALES --}}
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-2">
                <span class="fw-semibold small">Resumen Económico</span>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal:</span>
                    <span>S/ {{ number_format($compra->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">IGV (18%):</span>
                    <span>S/ {{ number_format($compra->igv, 2) }}</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between">
                    <span class="fw-bold">Total:</span>
                    <span class="fw-bold fs-5 text-success">S/ {{ number_format($compra->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- DETALLES --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-2">
                <span class="fw-semibold small">Productos Comprados</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Precio Unit.</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compra->detalles as $i => $det)
                        <tr>
                            <td class="text-muted">{{ $i + 1 }}</td>
                            <td class="fw-medium">{{ $det->producto_nombre }}</td>
                            <td class="text-center">{{ $det->cantidad }}</td>
                            <td class="text-end">S/ {{ number_format($det->precio_compra, 2) }}</td>
                            <td class="text-end fw-semibold">S/ {{ number_format($det->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total:</td>
                            <td class="text-end fw-bold text-success">
                                S/ {{ number_format($compra->total, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection