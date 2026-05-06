@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div>
                    <div class="text-muted small">Ventas Hoy</div>
                    <div class="fw-bold fs-5">S/ {{ number_format($ventas_hoy, 2) }}</div>
                    <div class="text-muted" style="font-size:0.75rem">
                        {{ $total_ventas_hoy }} transacciones
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div>
                    <div class="text-muted small">Ventas del Mes</div>
                    <div class="fw-bold fs-5">S/ {{ number_format($ventas_mes, 2) }}</div>
                    <div class="text-muted" style="font-size:0.75rem">
                        {{ now()->isoFormat('MMMM YYYY') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div>
                    <div class="text-muted small">Productos Activos</div>
                    <div class="fw-bold fs-5">{{ $productos_total }}</div>
                    <div class="text-muted" style="font-size:0.75rem">
                        en inventario
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div>
                    <div class="text-muted small">Stock Bajo</div>
                    <div class="fw-bold fs-5">{{ $stock_bajo }}</div>
                    <div class="text-muted" style="font-size:0.75rem">
                        productos por reabastecer
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- SEGUNDA FILA --}}
<div class="row g-3">

    {{-- ÚLTIMAS VENTAS --}}
    <div class="col-lg-7">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="fw-semibold"><i class="bi bi-receipt me-2 text-primary"></i>Últimas Ventas</span>
                <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-outline-primary">Ver todas</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Comprobante</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimas_ventas as $venta)
                            <tr>
                                <td>
                                    <span class="fw-medium">{{ $venta->numero_comprobante }}</span>
                                    <br><small class="text-muted">{{ $venta->created_at->diffForHumans() }}</small>
                                </td>
                                <td>{{ $venta->cliente->nombre }}</td>
                                <td class="fw-semibold text-success">S/ {{ number_format($venta->total, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $venta->estado === 'COMPLETADA' ? 'success' : 'danger' }}">
                                        {{ $venta->estado }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('ventas.show', $venta->id) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                    No hay ventas registradas hoy
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERTAS DE STOCK --}}
    <div class="col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="fw-semibold">
                    <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Alertas de Stock
                </span>
                <a href="{{ route('reportes.stock') }}" class="btn btn-sm btn-outline-warning">Ver reporte</a>
            </div>
            <div class="card-body p-0">

                @if($productos_sin_stock->count() > 0)
                <div class="px-3 pt-3">
                    <div class="alert alert-danger py-2 mb-2">
                        <i class="bi bi-x-circle me-1"></i>
                        <strong>Sin stock ({{ $productos_sin_stock->count() }})</strong>
                    </div>
                    @foreach($productos_sin_stock as $p)
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                        <div>
                            <span class="fw-medium small">{{ $p->nombre }}</span>
                            <br><span class="text-muted" style="font-size:0.75rem">{{ $p->categoria->nombre }}</span>
                        </div>
                        <span class="badge bg-danger">0 {{ $p->unidad }}</span>
                    </div>
                    @endforeach
                </div>
                @endif

                @if($productos_stock_bajo->count() > 0)
                <div class="px-3 pt-2 pb-3">
                    <div class="alert alert-warning py-2 mb-2">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>Stock bajo ({{ $productos_stock_bajo->count() }})</strong>
                    </div>
                    @foreach($productos_stock_bajo as $p)
                    <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
                        <div>
                            <span class="fw-medium small">{{ $p->nombre }}</span>
                            <br><span class="text-muted" style="font-size:0.75rem">Mín: {{ $p->stock_minimo }}</span>
                        </div>
                        <span class="badge bg-warning text-dark">{{ $p->stock }} {{ $p->unidad }}</span>
                    </div>
                    @endforeach
                </div>
                @endif

                @if($productos_sin_stock->count() === 0 && $productos_stock_bajo->count() === 0)
                <div class="text-center text-muted py-5">
                    <i class="bi bi-check-circle fs-3 text-success d-block mb-2"></i>
                    Todo el stock está en niveles óptimos
                </div>
                @endif

            </div>
        </div>
    </div>

</div>

@endsection
