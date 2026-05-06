@extends('layouts.app')

@section('title', 'Proveedores')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Proveedores</h5>
        <small class="text-muted">Gestión de proveedores</small>
    </div>
    <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
        <i class="bi bi-building-add me-1"></i> Nuevo Proveedor
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Razón Social</th>
                        <th>RUC</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proveedores as $prov)
                    <tr>
                        <td class="text-muted small">{{ $prov->id }}</td>
                        <td class="fw-medium">{{ $prov->razon_social }}</td>
                        <td><code>{{ $prov->ruc ?? '—' }}</code></td>
                        <td>{{ $prov->contacto ?? '—' }}</td>
                        <td>{{ $prov->telefono ?? '—' }}</td>
                        <td>{{ $prov->email ?? '—' }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $prov->activo ? 'success' : 'secondary' }}">
                                {{ $prov->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('proveedores.edit', $prov->id) }}"
                                   class="btn btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('proveedores.destroy', $prov->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Eliminar proveedor?')">
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
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-building fs-3 d-block mb-2"></i>
                            No hay proveedores registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($proveedores->hasPages())
    <div class="card-footer bg-white">{{ $proveedores->links() }}</div>
    @endif
</div>

@endsection