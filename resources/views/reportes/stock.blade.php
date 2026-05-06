@extends('layouts.app')

@section('title', 'Reporte de Stock')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold"><i class="bi bi-boxes me-2 text-warning"></i>Reporte de Stock</h5>
        <small class="text-muted">Inventario actual de productos</small>
    </div>
    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-printer me-1"></i> Imprimir
    </button>
</div>

{{-- TARJETAS RESUMEN --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Total Productos</div>
                <div class="fw-bold fs-4 text-primary">{{ $totales['productos'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Valor del Stock</div>
                <div class="fw-bold fs-4 text-success">S/ {{ number_format($totales['valor_total'], 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Stock Bajo</div>
                <div class="fw-bold fs-4 text-warning">{{ $totales['stock_bajo'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <div class="text-muted small mb-1">Sin Stock</div>
                <div class="fw-bold fs-4 text-danger">{{ $totales['sin_stock'] }}</div>
            </div>
        </div>
    </div>
</div>

{{-- FILTRO rápido --}}
<div class="card shadow-sm mb-3 no-print">
    <div class="card-body py-2">
        <div class="row g-2 align-items-center">
            <div class="col">
                <input type="text" id="filtroStock" class="form-control form-control-sm"
                       placeholder="Filtrar por nombre o categoría...">
            </div>
            <div class="col-auto">
                <div class="form-check form-check-inline mb-0">
                    <input class="form-check-input" type="checkbox" id="soloStockBajo">
                    <label class="form-check-label small" for="soloStockBajo">
                        <span class="text-warning fw-medium">Solo stock bajo/agotado</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TABLA --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 small" id="tablaStock">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th class="text-center">Unidad</th>
                        <th class="text-center">Stock Actual</th>
                        <th class="text-center">Stock Mín.</th>
                        <th class="text-end">P. Compra</th>
                        <th class="text-end">P. Venta</th>
                        <th class="text-end">Margen %</th>
                        <th class="text-end">Valor Stock</th>
                        <th class="text-center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $p)
                    @php
                        $estadoClase = $p->stock == 0
                            ? 'danger'
                            : ($p->tiene_stock_bajo ? 'warning' : 'success');
                        $estadoTexto = $p->stock == 0
                            ? 'Agotado'
                            : ($p->tiene_stock_bajo ? 'Stock bajo' : 'OK');
                        $filaCss = $p->stock == 0
                            ? 'table-danger'
                            : ($p->tiene_stock_bajo ? 'table-warning' : '');
                    @endphp
                    <tr class="{{ $filaCss }} fila-producto"
                        data-nombre="{{ strtolower($p->nombre) }}"
                        data-categoria="{{ strtolower($p->categoria->nombre ?? '') }}"
                        data-alerta="{{ $p->stock == 0 || $p->tiene_stock_bajo ? '1' : '0' }}">
                        <td class="text-muted font-monospace">{{ $p->codigo ?? '—' }}</td>
                        <td class="fw-medium">{{ $p->nombre }}</td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                {{ $p->categoria->nombre ?? 'Sin categoría' }}
                            </span>
                        </td>
                        <td class="text-center text-muted">{{ $p->unidad }}</td>
                        <td class="text-center fw-bold fs-6">
                            {{ $p->stock }}
                        </td>
                        <td class="text-center text-muted">{{ $p->stock_minimo }}</td>
                        <td class="text-end">S/ {{ number_format($p->precio_compra, 2) }}</td>
                        <td class="text-end">S/ {{ number_format($p->precio_venta, 2) }}</td>
                        <td class="text-end">
                            <span class="badge bg-{{ $p->margen >= 20 ? 'success' : ($p->margen >= 10 ? 'warning' : 'danger') }} bg-opacity-75">
                                {{ $p->margen }}%
                            </span>
                        </td>
                        <td class="text-end fw-semibold">S/ {{ number_format($p->valor_stock, 2) }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $estadoClase }}">{{ $estadoTexto }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-5">
                            <i class="bi bi-box-seam fs-3 d-block mb-2"></i>
                            No hay productos registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($productos->count())
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="9" class="text-end">Valor total del inventario:</td>
                        <td class="text-end text-success">S/ {{ number_format($totales['valor_total'], 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Filtro en tiempo real
document.getElementById('filtroStock').addEventListener('input', filtrar);
document.getElementById('soloStockBajo').addEventListener('change', filtrar);

function filtrar() {
    const texto = document.getElementById('filtroStock').value.toLowerCase();
    const soloAlerta = document.getElementById('soloStockBajo').checked;

    document.querySelectorAll('.fila-producto').forEach(fila => {
        const nombre    = fila.dataset.nombre;
        const categoria = fila.dataset.categoria;
        const esAlerta  = fila.dataset.alerta === '1';

        const coincideTexto = nombre.includes(texto) || categoria.includes(texto);
        const coincideAlerta = !soloAlerta || esAlerta;

        fila.style.display = (coincideTexto && coincideAlerta) ? '' : 'none';
    });
}
</script>

<style>
@media print {
    #sidebar, .topbar, .no-print { display: none !important; }
    #main-content { margin-left: 0 !important; }
    .card { box-shadow: none !important; border: 1px solid #ddd !important; }
}
</style>
@endpush
