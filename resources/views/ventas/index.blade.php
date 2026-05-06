@extends('layouts.app')

@section('title', 'Historial de Ventas')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Historial de Ventas</h5>
        <small class="text-muted">Todas las transacciones registradas</small>
    </div>
    <a href="{{ route('ventas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nueva Venta
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Comprobante</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Tipo</th>
                        <th>Pago</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Utilidad</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $venta)
                    <tr>
                        <td>
                            <span class="fw-medium text-primary">{{ $venta->numero_comprobante }}</span>
                            <br><small class="text-muted">{{ $venta->created_at->diffForHumans() }}</small>
                        </td>
                        <td>{{ $venta->fecha->format('d/m/Y') }}</td>
                        <td>{{ $venta->cliente->nombre }}</td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                {{ $venta->tipo_comprobante }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info">
                                {{ $venta->metodo_pago }}
                            </span>
                        </td>
                        <td class="text-end fw-bold text-success">
                            S/ {{ number_format($venta->total, 2) }}
                        </td>
                        <td class="text-end text-primary">
                            S/ {{ number_format($venta->utilidad, 2) }}
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $venta->estado === 'COMPLETADA' ? 'success' : 'danger' }}">
                                {{ $venta->estado }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('ventas.show', $venta->id) }}"
                                   class="btn btn-outline-info" title="Ver detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('ventas.ticket', $venta->id) }}"
                                   class="btn btn-outline-secondary" title="Ver ticket"
                                   target="_blank">
                                    <i class="bi bi-printer"></i>
                                </a>
                                @if($venta->estado === 'COMPLETADA')
                                <form action="{{ route('ventas.anular', $venta->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Anular esta venta? El stock se revertirá.')">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-outline-danger" title="Anular">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="bi bi-receipt fs-3 d-block mb-2"></i>
                            No hay ventas registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($ventas->hasPages())
    <div class="card-footer bg-white">{{ $ventas->links() }}</div>
    @endif
</div>

@endsection