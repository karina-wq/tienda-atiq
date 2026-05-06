@extends('layouts.app')

@section('title', 'Categorías')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Categorías</h5>
        <small class="text-muted">Organiza tus productos</small>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
        <i class="bi bi-plus-lg me-1"></i> Nueva Categoría
    </button>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th class="text-center">Productos</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categorias as $cat)
                <tr>
                    <td class="text-muted small">{{ $cat->id }}</td>
                    <td class="fw-medium">{{ $cat->nombre }}</td>
                    <td class="text-muted small">{{ $cat->descripcion ?? '—' }}</td>
                    <td class="text-center">
                        <span class="badge bg-primary bg-opacity-10 text-primary">
                            {{ $cat->productos_count }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-{{ $cat->activo ? 'success' : 'secondary' }}">
                            {{ $cat->activo ? 'Activa' : 'Inactiva' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary"
                                    onclick="abrirEditar({{ $cat->id }}, '{{ addslashes($cat->nombre) }}', '{{ addslashes($cat->descripcion) }}')"
                                    title="Editar">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('categorias.destroy', $cat->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('¿Eliminar categoría? Solo si no tiene productos.')">
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
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="bi bi-tags fs-3 d-block mb-2"></i>
                        No hay categorías registradas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categorias->hasPages())
    <div class="card-footer bg-white">{{ $categorias->links() }}</div>
    @endif
</div>

{{-- MODAL CREAR --}}
<div class="modal fade" id="modalCrear" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('categorias.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control"
                               placeholder="Nombre de la categoría" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="2"
                                  placeholder="Descripción opcional"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDITAR --}}
<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditar" method="POST">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Editar Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="editNombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" id="editDescripcion" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function abrirEditar(id, nombre, descripcion) {
    document.getElementById('formEditar').action = `/categorias/${id}`;
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editDescripcion').value = descripcion;
    new bootstrap.Modal(document.getElementById('modalEditar')).show();
}
</script>
@endpush
