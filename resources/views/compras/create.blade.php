@extends('layouts.app')

@section('title', 'Nueva Compra')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Nueva Compra</h5>
        <small class="text-muted">Registro de entrada de mercadería</small>
    </div>
    <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

<form action="{{ route('compras.store') }}" method="POST" id="formCompra">
@csrf

<div class="row g-3">

    {{-- CABECERA --}}
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-semibold"><i class="bi bi-file-text me-2 text-primary"></i>Datos del Comprobante</span>
            </div>
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Proveedor <span class="text-danger">*</span></label>
                        <select name="proveedor_id"
                                class="form-select @error('proveedor_id') is-invalid @enderror">
                            <option value="">-- Seleccionar --</option>
                            @foreach($proveedores as $prov)
                                <option value="{{ $prov->id }}"
                                    {{ old('proveedor_id') == $prov->id ? 'selected':'' }}>
                                    {{ $prov->razon_social }}
                                </option>
                            @endforeach
                        </select>
                        @error('proveedor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Tipo Comprobante</label>
                        <select name="tipo_comprobante" class="form-select">
                            @foreach(['FACTURA','BOLETA','TICKET'] as $t)
                                <option value="{{ $t }}" {{ old('tipo_comprobante','FACTURA') == $t ? 'selected':'' }}>
                                    {{ $t }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">N° Comprobante</label>
                        <input type="text" name="numero_comprobante" class="form-control"
                               value="{{ old('numero_comprobante') }}" placeholder="F001-00001">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha <span class="text-danger">*</span></label>
                        <input type="date" name="fecha"
                               class="form-control @error('fecha') is-invalid @enderror"
                               value="{{ old('fecha', date('Y-m-d')) }}">
                        @error('fecha')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Observaciones</label>
                        <input type="text" name="observaciones" class="form-control"
                               value="{{ old('observaciones') }}" placeholder="Opcional">
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- TOTALES --}}
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <span class="fw-semibold"><i class="bi bi-calculator me-2 text-success"></i>Resumen</span>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal:</span>
                    <span class="fw-semibold" id="resumen_subtotal">S/ 0.00</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="fw-bold fs-5">Total:</span>
                    <span class="fw-bold fs-5 text-success" id="resumen_total">S/ 0.00</span>
                </div>
                <div class="mt-3">
                    <span class="text-muted small" id="resumen_items">0 productos agregados</span>
                </div>
            </div>
            <div class="card-footer bg-white">
                <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-check-lg me-1"></i> Registrar Compra
                </button>
            </div>
        </div>
    </div>

    {{-- BUSCADOR DE PRODUCTOS --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-semibold"><i class="bi bi-search me-2 text-primary"></i>Agregar Productos</span>
            </div>
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label">Buscar Producto</label>
                        <select id="selectProducto" class="form-select">
                            <option value="">-- Seleccionar producto --</option>
                            @foreach($productos as $prod)
                                <option value="{{ $prod->id }}"
                                        data-nombre="{{ $prod->nombre }}"
                                        data-precio="{{ $prod->precio_compra }}"
                                        data-codigo="{{ $prod->codigo }}">
                                    [{{ $prod->codigo }}] {{ $prod->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Cantidad</label>
                        <input type="number" id="inputCantidad" class="form-control"
                               value="1" min="1">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Precio Compra</label>
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input type="number" id="inputPrecio" class="form-control"
                                   value="0.00" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary w-100" onclick="agregarProducto()">
                            <i class="bi bi-plus-lg me-1"></i> Agregar al detalle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLA DE DETALLES --}}
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-semibold"><i class="bi bi-list-ul me-2"></i>Detalle de Compra</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0" id="tablaDetalles">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Precio Unit.</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="detalleBody">
                        <tr id="filaVacia">
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                Agregue productos al detalle
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</form>

@endsection

@push('scripts')
<script>
let detalles  = [];
let itemIndex = 0;

// Autocompletar precio al seleccionar producto
document.getElementById('selectProducto').addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    if (opt.value) {
        document.getElementById('inputPrecio').value = parseFloat(opt.dataset.precio).toFixed(2);
    }
});

function agregarProducto() {
    const select   = document.getElementById('selectProducto');
    const opt      = select.options[select.selectedIndex];
    const cantidad = parseInt(document.getElementById('inputCantidad').value);
    const precio   = parseFloat(document.getElementById('inputPrecio').value);

    if (!opt.value) { alert('Seleccione un producto.'); return; }
    if (cantidad < 1) { alert('La cantidad debe ser mínimo 1.'); return; }
    if (precio <= 0)  { alert('El precio debe ser mayor a cero.'); return; }

    // Verificar si ya existe
    const existe = detalles.find(d => d.producto_id === opt.value);
    if (existe) { alert('Este producto ya está en el detalle. Elimínelo y agréguelo de nuevo.'); return; }

    const subtotal = (cantidad * precio).toFixed(2);
    const item = {
        index:       itemIndex,
        producto_id: opt.value,
        codigo:      opt.dataset.codigo,
        nombre:      opt.dataset.nombre,
        cantidad:    cantidad,
        precio:      precio,
        subtotal:    parseFloat(subtotal),
    };
    detalles.push(item);
    renderTabla();
    itemIndex++;

    // Limpiar
    select.value = '';
    document.getElementById('inputCantidad').value = 1;
    document.getElementById('inputPrecio').value   = '0.00';
}

function eliminarItem(index) {
    detalles = detalles.filter(d => d.index !== index);
    renderTabla();
}

function renderTabla() {
    const tbody = document.getElementById('detalleBody');
    tbody.innerHTML = '';

    if (detalles.length === 0) {
        tbody.innerHTML = `<tr id="filaVacia">
            <td colspan="6" class="text-center text-muted py-4">
                <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                Agregue productos al detalle
            </td></tr>`;
        actualizarTotales();
        return;
    }

    detalles.forEach((d, i) => {
        tbody.innerHTML += `
        <tr>
            <td><code>${d.codigo}</code></td>
            <td class="fw-medium">${d.nombre}
                <input type="hidden" name="detalles[${i}][producto_id]"   value="${d.producto_id}">
                <input type="hidden" name="detalles[${i}][cantidad]"       value="${d.cantidad}">
                <input type="hidden" name="detalles[${i}][precio_compra]"  value="${d.precio}">
            </td>
            <td class="text-center">${d.cantidad}</td>
            <td class="text-end">S/ ${parseFloat(d.precio).toFixed(2)}</td>
            <td class="text-end fw-semibold">S/ ${d.subtotal.toFixed(2)}</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger"
                        onclick="eliminarItem(${d.index})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>`;
    });

    actualizarTotales();
}

function actualizarTotales() {
    const subtotal = detalles.reduce((s, d) => s + d.subtotal, 0);
    const total    = subtotal;

    document.getElementById('resumen_subtotal').textContent = 'S/ ' + subtotal.toFixed(2);
    document.getElementById('resumen_total').textContent    = 'S/ ' + total.toFixed(2);
    document.getElementById('resumen_items').textContent    =
        detalles.length + ' producto(s) agregado(s)';
}

document.getElementById('formCompra').addEventListener('submit', function (e) {
    if (detalles.length === 0) {
        e.preventDefault();
        alert('Debe agregar al menos un producto al detalle.');
    }
});
</script>
@endpush