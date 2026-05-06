@extends('layouts.app')

@section('title', 'Compras')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Compras</h5>
        <small class="text-muted">Registro de entrada de mercadería</small>
    </div>
    <a href="{{ route('compras.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nueva Compra
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Comprobante</th>
                        <th class="text-center">Items</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-end">IGV</th>
                        <th class="text-end">Total</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($compras as $compra)
                    <tr>
                        <td class="text-muted small">{{ $compra->id }}</td>
                        <td>{{ $compra->fecha->format('d/m/Y') }}</td>
                        <td class="fw-medium">{{ $compra->proveedor->razon_social }}</td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary me-1">
                                {{ $compra->tipo_comprobante }}
                            </span>
                            {{ $compra->numero_comprobante ?? '—' }}
                        </td>
                        <td class="text-center">{{ $compra->detalles->count() }}</td>
                        <td class="text-end">S/ {{ number_format($compra->subtotal, 2) }}</td>
                        <td class="text-end text-muted">S/ {{ number_format($compra->igv, 2) }}</td>
                        <td class="text-end fw-bold">S/ {{ number_format($compra->total, 2) }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $compra->estado === 'COMPLETADA' ? 'success' : 'danger' }}">
                                {{ $compra->estado }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('compras.show', $compra->id) }}"
                                   class="btn btn-outline-info" title="Ver detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($compra->estado === 'COMPLETADA')
                                <form action="{{ route('compras.anular', $compra->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Anular esta compra? El stock se revertirá.')">
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
                        <td colspan="10" class="text-center text-muted py-5">
                            <i class="bi bi-truck fs-3 d-block mb-2"></i>
                            No hay compras registradas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($compras->hasPages())
    <div class="card-footer bg-white">{{ $compras->links() }}</div>
    @endif
</div>

@endsection