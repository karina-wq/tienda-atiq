@extends('layouts.app')

@section('title', 'Reporte de Ventas')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Reporte de Ventas</h5>
        <small class="text-muted">Ventas completadas del período seleccionado</small>
    </div>
    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-printer me-1"></i> Imprimir
    </button>
</div>

{{-- FILTRO FECHAS --}}
<div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('reportes.ventas') }}" class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label small mb-1">Desde</label>
                <input type="date" name="inicio" class="form-control form-control-sm"
                       value="{{ $inicio }}">
            </div>
            <div class="col-auto">
                <label class="form-label small mb-1">Hasta</label>
                <input type="date" name="fin" class="form-control form-control-sm"
                       value="{{ $fin }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-funnel me-1"></i>Filtrar
                </button>
            </div>
            <div class="col-auto">
                <a href="{{ route('reportes.ventas') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Este mes
                </a>
            </div>
        </form>
    </div>
</div>

{{-- TARJETAS RESUMEN --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Total Vendido</div>
                <div class="fw-bold fs-4 text-success">S/ {{ number_format($totales['ventas'], 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">N° Ventas</div>
                <div class="fw-bold fs-4 text-primary">{{ $totales['cantidad'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Descuentos</div>
                <div class="fw-bold fs-4 text-danger">S/ {{ number_format($totales['descuentos'], 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Utilidad Bruta</div>
                <div class="fw-bold fs-4 text-info">S/ {{ number_format($totales['utilidad'], 2) }}</div>
            </div>
        </div>
    </div>
</div>

{{-- TABLA --}}
<div class="card shadow-sm">
    <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
        <span class="fw-semibold small">
            Período: {{ \Carbon\Carbon::parse($inicio)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($fin)->format('d/m/Y') }}
        </span>
        <span class="badge bg-secondary">{{ $totales['cantidad'] }} ventas</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Comprobante</th>
                        <th>Cliente</th>
                        <th class="text-center">Método</th>
                        <th class="text-center">Items</th>
                        <th class="text-end">Descuento</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Utilidad</th>
                        <th class="text-center no-print">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $venta)
                    <tr>
                        <td>{{ $venta->fecha->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $venta->tipo_comprobante }}</span>
                            {{ $venta->numero_comprobante }}
                        </td>
                        <td>{{ $venta->cliente->nombre ?? '—' }}</td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">{{ $venta->metodo_pago }}</span>
                        </td>
                        <td class="text-center">{{ $venta->detalles->count() }}</td>
                        <td class="text-end text-danger">
                            {{ $venta->descuento > 0 ? 'S/ ' . number_format($venta->descuento, 2) : '—' }}
                        </td>
                        <td class="text-end fw-semibold">S/ {{ number_format($venta->total, 2) }}</td>
                        <td class="text-end text-info">S/ {{ number_format($venta->utilidad, 2) }}</td>
                        <td class="text-center no-print">
                            <a href="{{ route('ventas.show', $venta->id) }}"
                               class="btn btn-outline-info btn-sm" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bi bi-bar-chart fs-3 d-block mb-2"></i>
                            No hay ventas en el período seleccionado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($ventas->count())
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="6" class="text-end">Totales:</td>
                        <td class="text-end text-success">S/ {{ number_format($totales['ventas'], 2) }}</td>
                        <td class="text-end text-info">S/ {{ number_format($totales['utilidad'], 2) }}</td>
                        <td class="no-print"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<style>
@media print {
    #sidebar, .topbar, .no-print { display: none !important; }
    #main-content { margin-left: 0 !important; }
    .card { box-shadow: none !important; border: 1px solid #ddd !important; }
}
</style>
@endpush
