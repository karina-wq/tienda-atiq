@extends('layouts.app')

@section('title', 'Kardex: ' . $producto->nombre)

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Kardex de Producto</h5>
        <small class="text-muted">{{ $producto->codigo }} — {{ $producto->nombre }}</small>
    </div>
    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

{{-- INFO PRODUCTO --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <div class="text-muted small mb-1">Stock Actual</div>
                <div class="display-6 fw-bold
                    {{ $producto->stock == 0 ? 'text-danger' :
                       ($producto->tiene_stock_bajo ? 'text-warning' : 'text-success') }}">
                    {{ $producto->stock }}
                </div>
                <div class="text-muted">{{ $producto->unidad }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <div class="text-muted small mb-1">Precio Compra</div>
                <div class="fs-4 fw-bold">S/ {{ number_format($producto->precio_compra, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <div class="text-muted small mb-1">Precio Venta</div>
                <div class="fs-4 fw-bold text-success">S/ {{ number_format($producto->precio_venta, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <div class="text-muted small mb-1">Margen</div>
                <div class="fs-4 fw-bold text-primary">{{ $producto->margen }}%</div>
            </div>
        </div>
    </div>
</div>

{{-- MOVIMIENTOS --}}
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <span class="fw-semibold"><i class="bi bi-clock-history me-2 text-primary"></i>Historial de Movimientos</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Origen</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Stock Anterior</th>
                        <th class="text-center">Stock Nuevo</th>
                        <th class="text-end">Costo Unit.</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movimientos as $mov)
                    <tr>
                        <td>
                            <small>{{ $mov->created_at->format('d/m/Y H:i') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-{{ $mov->tipo === 'ENTRADA' ? 'success' :
                                ($mov->tipo === 'SALIDA' ? 'danger' : 'warning text-dark') }}">
                                <i class="bi bi-{{ $mov->tipo === 'ENTRADA' ? 'arrow-down' : 'arrow-up' }}"></i>
                                {{ $mov->tipo }}
                            </span>
                        </td>
                        <td><small class="text-muted">{{ $mov->origen }}</small></td>
                        <td class="text-center fw-bold
                            {{ $mov->tipo === 'ENTRADA' ? 'text-success' : 'text-danger' }}">
                            {{ $mov->tipo === 'ENTRADA' ? '+' : '-' }}{{ $mov->cantidad }}
                        </td>
                        <td class="text-center text-muted">{{ $mov->stock_anterior }}</td>
                        <td class="text-center fw-semibold">{{ $mov->stock_nuevo }}</td>
                        <td class="text-end">S/ {{ number_format($mov->costo_unitario, 2) }}</td>
                        <td><small class="text-muted">{{ $mov->observacion ?? '—' }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            Sin movimientos registrados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($movimientos->hasPages())
    <div class="card-footer bg-white">
        {{ $movimientos->links() }}
    </div>
    @endif
</div>

@endsection
