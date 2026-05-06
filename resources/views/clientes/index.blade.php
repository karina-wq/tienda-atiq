@extends('layouts.app')

@section('title', 'Clientes')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Clientes</h5>
        <small class="text-muted">Gestión de clientes</small>
    </div>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i> Nuevo Cliente
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Documento</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                    <tr>
                        <td class="text-muted small">{{ $cliente->id }}</td>
                        <td>
                            <span class="fw-medium">{{ $cliente->nombre }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary me-1">
                                {{ $cliente->tipo_documento }}
                            </span>
                            {{ $cliente->numero_documento ?? '—' }}
                        </td>
                        <td>{{ $cliente->telefono ?? '—' }}</td>
                        <td>{{ $cliente->email ?? '—' }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $cliente->activo ? 'success' : 'secondary' }}">
                                {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('clientes.edit', $cliente->id) }}"
                                   class="btn btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('clientes.destroy', $cliente->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Eliminar este cliente?')">
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
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-people fs-3 d-block mb-2"></i>
                            No hay clientes registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($clientes->hasPages())
    <div class="card-footer bg-white">{{ $clientes->links() }}</div>
    @endif
</div>

@endsection