@extends('layouts.app')

@section('title', 'Reporte de Utilidades')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold"><i class="bi bi-currency-dollar me-2 text-success"></i>Reporte de Utilidades</h5>
        <small class="text-muted">Rentabilidad por producto en el período seleccionado</small>
    </div>
    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-printer me-1"></i> Imprimir
    </button>
</div>

{{-- FILTRO FECHAS --}}
<div class="card shadow-sm mb-3 no-print">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('reportes.utilidades') }}" class="row g-2 align-items-end">
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
                <a href="{{ route('reportes.utilidades') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Este mes
                </a>
            </div>
        </form>
    </div>
</div>

{{-- TARJETAS RESUMEN --}}
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Total Ingresos</div>
                <div class="fw-bold fs-4 text-primary">S/ {{ number_format($totales['ingresos'], 2) }}</div>
                <small class="text-muted">ventas brutas</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Total Costos</div>
                <div class="fw-bold fs-4 text-danger">S/ {{ number_format($totales['costos'], 2) }}</div>
                <small class="text-muted">costo de ventas</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-start border-4 border-success h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Utilidad Bruta</div>
                <div class="fw-bold fs-3 text-success">S/ {{ number_format($totales['utilidad'], 2) }}</div>
                @if($totales['ingresos'] > 0)
                <small class="text-muted">
                    Margen: {{ round(($totales['utilidad'] / $totales['ingresos']) * 100, 1) }}%
                </small>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- GRÁFICO MINI (barras CSS) --}}
@if($detalles->count() > 0)
<div class="card shadow-sm mb-3 no-print">
    <div class="card-header bg-white py-2">
        <span class="fw-semibold small"><i class="bi bi-bar-chart me-2 text-success"></i>Top 5 productos por utilidad</span>
    </div>
    <div class="card-body">
        @php $maxUtil = $detalles->take(5)->max('utilidad') ?: 1; @endphp
        @foreach($detalles->take(5) as $item)
        <div class="mb-2">
            <div class="d-flex justify-content-between small mb-1">
                <span class="fw-medium text-truncate" style="max-width:200px">{{ $item['producto'] }}</span>
                <span class="text-success fw-bold">S/ {{ number_format($item['utilidad'], 2) }}</span>
            </div>
            <div class="progress" style="height:10px;">
                <div class="progress-bar bg-success"
                     style="width: {{ ($item['utilidad'] / $maxUtil) * 100 }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- TABLA --}}
<div class="card shadow-sm">
    <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
        <span class="fw-semibold small">
            Período: {{ \Carbon\Carbon::parse($inicio)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($fin)->format('d/m/Y') }}
        </span>
        <span class="badge bg-secondary">{{ $detalles->count() }} productos</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th class="text-center">Uds. Vendidas</th>
                        <th class="text-end">Ingresos</th>
                        <th class="text-end">Costos</th>
                        <th class="text-end">Utilidad</th>
                        <th class="text-center">Margen</th>
                        <th class="text-center no-print">%&nbsp;Contrib.</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detalles as $i => $item)
                    @php
                        $margen = $item['ingresos'] > 0
                            ? round(($item['utilidad'] / $item['ingresos']) * 100, 1)
                            : 0;
                        $contribucion = $totales['utilidad'] > 0
                            ? round(($item['utilidad'] / $totales['utilidad']) * 100, 1)
                            : 0;
                        $margenClase = $margen >= 30 ? 'success' : ($margen >= 15 ? 'warning' : 'danger');
                    @endphp
                    <tr>
                        <td class="text-muted">{{ $i + 1 }}</td>
                        <td class="fw-medium">{{ $item['producto'] }}</td>
                        <td class="text-center">{{ $item['cantidad'] }}</td>
                        <td class="text-end">S/ {{ number_format($item['ingresos'], 2) }}</td>
                        <td class="text-end text-danger">S/ {{ number_format($item['costo'], 2) }}</td>
                        <td class="text-end fw-bold text-success">S/ {{ number_format($item['utilidad'], 2) }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $margenClase }}">{{ $margen }}%</span>
                        </td>
                        <td class="text-center no-print">
                            <div class="d-flex align-items-center gap-1">
                                <div class="progress flex-grow-1" style="height:6px;">
                                    <div class="progress-bar bg-success"
                                         style="width:{{ $contribucion }}%"></div>
                                </div>
                                <small class="text-muted" style="min-width:32px">{{ $contribucion }}%</small>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-currency-dollar fs-3 d-block mb-2"></i>
                            No hay datos de utilidades en el período seleccionado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($detalles->count())
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="3" class="text-end">Totales:</td>
                        <td class="text-end text-primary">S/ {{ number_format($totales['ingresos'], 2) }}</td>
                        <td class="text-end text-danger">S/ {{ number_format($totales['costos'], 2) }}</td>
                        <td class="text-end text-success">S/ {{ number_format($totales['utilidad'], 2) }}</td>
                        <td class="text-center">
                            @if($totales['ingresos'] > 0)
                            <span class="badge bg-success">
                                {{ round(($totales['utilidad'] / $totales['ingresos']) * 100, 1) }}%
                            </span>
                            @endif
                        </td>
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
