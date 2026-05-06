@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Editar Producto</h5>
        <small class="text-muted">{{ $producto->codigo }} — {{ $producto->nombre }}</small>
    </div>
    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('productos.update', $producto->id) }}" method="POST">
            @csrf @method('PUT')

            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Código <span class="text-danger">*</span></label>
                    <input type="text" name="codigo"
                           class="form-control @error('codigo') is-invalid @enderror"
                           value="{{ old('codigo', $producto->codigo) }}">
                    @error('codigo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                    <input type="text" name="nombre"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $producto->nombre) }}">
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Categoría <span class="text-danger">*</span></label>
                    <select name="categoria_id" class="form-select">
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('categoria_id', $producto->categoria_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Unidad</label>
                    <select name="unidad" class="form-select">
                        @foreach(['UND','KG','LT','MT','CJA','PAQ'] as $u)
                            <option value="{{ $u }}"
                                {{ old('unidad', $producto->unidad) == $u ? 'selected' : '' }}>
                                {{ $u }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Stock Mínimo</label>
                    <input type="number" name="stock_minimo"
                           class="form-control"
                           value="{{ old('stock_minimo', $producto->stock_minimo) }}" min="0">
                    <small class="text-muted">Stock actual: <strong>{{ $producto->stock }}</strong></small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Precio de Compra <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">S/</span>
                        <input type="number" name="precio_compra" id="precio_compra"
                               class="form-control"
                               value="{{ old('precio_compra', $producto->precio_compra) }}"
                               step="0.01" min="0" oninput="calcularMargen()">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Precio de Venta <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">S/</span>
                        <input type="number" name="precio_venta" id="precio_venta"
                               class="form-control"
                               value="{{ old('precio_venta', $producto->precio_venta) }}"
                               step="0.01" min="0" oninput="calcularMargen()">
                    </div>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Margen</label>
                    <input type="text" id="margen_display"
                           class="form-control bg-light fw-bold" readonly
                           value="{{ $producto->margen }}%">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Estado</label>
                    <select name="activo" class="form-select">
                        <option value="1" {{ $producto->activo ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ !$producto->activo ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="2">{{ old('descripcion', $producto->descripcion) }}</textarea>
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1"></i> Actualizar Producto
                </button>
                <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function calcularMargen() {
    const compra = parseFloat(document.getElementById('precio_compra').value) || 0;
    const venta  = parseFloat(document.getElementById('precio_venta').value) || 0;
    let margen   = 0;
    if (compra > 0) margen = ((venta - compra) / compra * 100).toFixed(1);
    const el = document.getElementById('margen_display');
    el.value = margen + '%';
    el.className = 'form-control bg-light fw-bold ' +
        (margen >= 20 ? 'text-success' : margen > 0 ? 'text-warning' : 'text-danger');
}
</script>
@endpush
