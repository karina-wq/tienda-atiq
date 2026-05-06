@extends('layouts.app')

@section('title', 'Productos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Productos</h5>
        <small class="text-muted">Gestión de inventario</small>
    </div>
    <a href="{{ route('productos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Producto
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th class="text-end">P. Compra</th>
                        <th class="text-end">P. Venta</th>
                        <th class="text-end">Margen</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                    <tr>
                        <td class="text-muted small">{{ $producto->id }}</td>
                        <td><code>{{ $producto->codigo }}</code></td>
                        <td>
                            <span class="fw-medium">{{ $producto->nombre }}</span>
                            @if($producto->descripcion)
                                <br><small class="text-muted">{{ Str::limit($producto->descripcion, 40) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                {{ $producto->categoria->nombre }}
                            </span>
                        </td>
                        <td class="text-end">S/ {{ number_format($producto->precio_compra, 2) }}</td>
                        <td class="text-end fw-semibold">S/ {{ number_format($producto->precio_venta, 2) }}</td>
                        <td class="text-end">
                            <span class="badge bg-{{ $producto->margen >= 20 ? 'success' : 'warning' }} bg-opacity-75">
                                {{ $producto->margen }}%
                            </span>
                        </td>
                        <td class="text-center">
                            @if($producto->stock == 0)
                                <span class="badge bg-danger">Sin stock</span>
                            @elseif($producto->tiene_stock_bajo)
                                <span class="badge bg-warning text-dark">
                                    {{ $producto->stock }} {{ $producto->unidad }}
                                </span>
                            @else
                                <span class="badge bg-success">
                                    {{ $producto->stock }} {{ $producto->unidad }}
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $producto->activo ? 'success' : 'secondary' }}">
                                {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('productos.show', $producto->id) }}"
                                   class="btn btn-outline-info" title="Ver kardex">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('productos.edit', $producto->id) }}"
                                   class="btn btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('productos.destroy', $producto->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Eliminar este producto?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-5">
                            <i class="bi bi-box-seam fs-3 d-block mb-2"></i>
                            No hay productos registrados.
                            <a href="{{ route('productos.create') }}">Crear el primero</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($productos->hasPages())
    <div class="card-footer bg-white">
        {{ $productos->links() }}
    </div>
    @endif
</div>

@endsection
