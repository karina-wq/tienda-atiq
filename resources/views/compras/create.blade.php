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

                    {{-- PROVEEDOR con botón "Nuevo" --}}
                    <div class="col-md-6">
                        <label class="form-label">Proveedor <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="proveedor_id" id="selectProveedor"
                                    class="form-select @error('proveedor_id') is-invalid @enderror">
                                <option value="">-- Seleccionar --</option>
                                @foreach($proveedores as $prov)
                                    <option value="{{ $prov->id }}"
                                        {{ old('proveedor_id') == $prov->id ? 'selected':'' }}>
                                        {{ $prov->razon_social }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-primary" title="Nuevo proveedor"
                                    data-bs-toggle="modal" data-bs-target="#modalProveedor">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                        @error('proveedor_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
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
                        <div class="input-group">
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
                            <button type="button" class="btn btn-outline-primary" title="Nuevo producto"
                                    data-bs-toggle="modal" data-bs-target="#modalProducto">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
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

{{-- ============================================================ --}}
{{-- MODAL: NUEVO PROVEEDOR                                        --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalProveedor" tabindex="-1" aria-labelledby="modalProveedorLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProveedorLabel">
                    <i class="bi bi-person-plus me-2 text-primary"></i>Nuevo Proveedor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="alertaProveedor"></div>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Razón Social <span class="text-danger">*</span></label>
                        <input type="text" id="prov_razon_social" class="form-control" placeholder="Nombre de la empresa">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">RUC</label>
                        <input type="text" id="prov_ruc" class="form-control" placeholder="20000000000" maxlength="11">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Persona de Contacto</label>
                        <input type="text" id="prov_contacto" class="form-control" placeholder="Nombre del contacto">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Teléfono</label>
                        <input type="text" id="prov_telefono" class="form-control" placeholder="999 999 999">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" id="prov_email" class="form-control" placeholder="proveedor@email.com">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Dirección</label>
                        <input type="text" id="prov_direccion" class="form-control" placeholder="Dirección completa">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarProveedor()">
                    <i class="bi bi-save me-1"></i> Guardar Proveedor
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL: NUEVO PRODUCTO                                         --}}
{{-- ============================================================ --}}
<div class="modal fade" id="modalProducto" tabindex="-1" aria-labelledby="modalProductoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProductoLabel">
                    <i class="bi bi-box-seam me-2 text-primary"></i>Nuevo Producto
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="alertaProducto"></div>

                {{-- Código generado automáticamente --}}
                <div class="alert alert-info d-flex align-items-center py-2 mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    <span>El código se genera automáticamente: <strong id="codigoPreview">cargando...</strong></span>
                </div>

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                        <input type="text" id="prod_nombre" class="form-control" placeholder="Nombre completo del producto">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Categoría <span class="text-danger">*</span></label>
                        <select id="prod_categoria_id" class="form-select">
                            <option value="">-- Seleccionar --</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Unidad</label>
                        <select id="prod_unidad" class="form-select">
                            <option value="UND">UND</option>
                            <option value="KG">KG</option>
                            <option value="LT">LT</option>
                            <option value="MT">MT</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Precio Compra <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input type="number" id="prod_precio_compra" class="form-control"
                                   value="0.00" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Precio Venta <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input type="number" id="prod_precio_venta" class="form-control"
                                   value="0.00" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Stock Mínimo</label>
                        <input type="number" id="prod_stock_minimo" class="form-control" value="5" min="0">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descripción</label>
                        <textarea id="prod_descripcion" class="form-control" rows="2"
                                  placeholder="Descripción opcional del producto"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarProducto()">
                    <i class="bi bi-save me-1"></i> Guardar Producto
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* ============================================================
   DETALLE DE COMPRA
   ============================================================ */
let detalles  = [];
let itemIndex = 0;

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

    if (!opt.value)   { alert('Seleccione un producto.'); return; }
    if (cantidad < 1) { alert('La cantidad debe ser mínimo 1.'); return; }
    if (precio <= 0)  { alert('El precio debe ser mayor a cero.'); return; }

    const existe = detalles.find(d => d.producto_id === opt.value);
    if (existe) { alert('Este producto ya está en el detalle. Elimínelo y agréguelo de nuevo.'); return; }

    const item = {
        index:       itemIndex,
        producto_id: opt.value,
        codigo:      opt.dataset.codigo,
        nombre:      opt.dataset.nombre,
        cantidad:    cantidad,
        precio:      precio,
        subtotal:    parseFloat((cantidad * precio).toFixed(2)),
    };
    detalles.push(item);
    renderTabla();
    itemIndex++;

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
                <input type="hidden" name="detalles[${i}][producto_id]"  value="${d.producto_id}">
                <input type="hidden" name="detalles[${i}][cantidad]"      value="${d.cantidad}">
                <input type="hidden" name="detalles[${i}][precio_compra]" value="${d.precio}">
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
    document.getElementById('resumen_subtotal').textContent = 'S/ ' + subtotal.toFixed(2);
    document.getElementById('resumen_total').textContent    = 'S/ ' + subtotal.toFixed(2);
    document.getElementById('resumen_items').textContent    = detalles.length + ' producto(s) agregado(s)';
}

document.getElementById('formCompra').addEventListener('submit', function (e) {
    if (detalles.length === 0) {
        e.preventDefault();
        alert('Debe agregar al menos un producto al detalle.');
    }
});

/* ============================================================
   MODAL: NUEVO PROVEEDOR
   ============================================================ */
function guardarProveedor() {
    const razonSocial = document.getElementById('prov_razon_social').value.trim();
    const alerta      = document.getElementById('alertaProveedor');

    if (!razonSocial) {
        alerta.innerHTML = `<div class="alert alert-danger py-2">La razón social es obligatoria.</div>`;
        return;
    }
    alerta.innerHTML = '';

    fetch('{{ route("proveedores.store-ajax") }}', {
        method:  'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({
            razon_social: razonSocial,
            ruc:          document.getElementById('prov_ruc').value.trim()       || null,
            contacto:     document.getElementById('prov_contacto').value.trim()  || null,
            telefono:     document.getElementById('prov_telefono').value.trim()  || null,
            email:        document.getElementById('prov_email').value.trim()     || null,
            direccion:    document.getElementById('prov_direccion').value.trim() || null,
        }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Agregar al select de proveedores y seleccionarlo
            const select = document.getElementById('selectProveedor');
            const option = new Option(data.nombre, data.id, true, true);
            select.appendChild(option);
            select.value = data.id;

            // Limpiar y cerrar modal
            limpiarModalProveedor();
            bootstrap.Modal.getInstance(document.getElementById('modalProveedor')).hide();
        } else {
            const errores = data.errors ? Object.values(data.errors).flat().join('<br>') : 'Error al guardar.';
            alerta.innerHTML = `<div class="alert alert-danger py-2">${errores}</div>`;
        }
    })
    .catch(() => {
        alerta.innerHTML = `<div class="alert alert-danger py-2">Error de conexión.</div>`;
    });
}

function limpiarModalProveedor() {
    ['prov_razon_social','prov_ruc','prov_contacto','prov_telefono','prov_email','prov_direccion']
        .forEach(id => document.getElementById(id).value = '');
    document.getElementById('alertaProveedor').innerHTML = '';
}

/* ============================================================
   MODAL: NUEVO PRODUCTO
   ============================================================ */

// Al abrir el modal, cargar el próximo código
document.getElementById('modalProducto').addEventListener('show.bs.modal', function () {
    fetch('{{ route("productos.proximo-codigo") }}')
        .then(r => r.json())
        .then(data => {
            document.getElementById('codigoPreview').textContent = data.codigo;
        });
});

function guardarProducto() {
    const nombre      = document.getElementById('prod_nombre').value.trim();
    const categoriaId = document.getElementById('prod_categoria_id').value;
    const alerta      = document.getElementById('alertaProducto');

    if (!nombre) {
        alerta.innerHTML = `<div class="alert alert-danger py-2">El nombre del producto es obligatorio.</div>`;
        return;
    }
    if (!categoriaId) {
        alerta.innerHTML = `<div class="alert alert-danger py-2">Seleccione una categoría.</div>`;
        return;
    }
    alerta.innerHTML = '';

    fetch('{{ route("productos.store-ajax") }}', {
        method:  'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({
            nombre:         nombre,
            categoria_id:   categoriaId,
            unidad:         document.getElementById('prod_unidad').value,
            precio_compra:  parseFloat(document.getElementById('prod_precio_compra').value) || 0,
            precio_venta:   parseFloat(document.getElementById('prod_precio_venta').value)  || 0,
            stock_minimo:   parseInt(document.getElementById('prod_stock_minimo').value)     || 5,
            descripcion:    document.getElementById('prod_descripcion').value.trim()         || null,
        }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Agregar al select de productos y seleccionarlo
            const select = document.getElementById('selectProducto');
            const option = new Option(
                `[${data.codigo}] ${data.nombre}`,
                data.id, true, true
            );
            option.dataset.nombre  = data.nombre;
            option.dataset.precio  = data.precio_compra;
            option.dataset.codigo  = data.codigo;
            select.appendChild(option);
            select.value = data.id;

            // Autocompletar precio
            document.getElementById('inputPrecio').value = parseFloat(data.precio_compra).toFixed(2);

            // Limpiar y cerrar modal
            limpiarModalProducto();
            bootstrap.Modal.getInstance(document.getElementById('modalProducto')).hide();
        } else {
            const errores = data.errors ? Object.values(data.errors).flat().join('<br>') : 'Error al guardar.';
            alerta.innerHTML = `<div class="alert alert-danger py-2">${errores}</div>`;
        }
    })
    .catch(() => {
        alerta.innerHTML = `<div class="alert alert-danger py-2">Error de conexión.</div>`;
    });
}

function limpiarModalProducto() {
    document.getElementById('prod_nombre').value         = '';
    document.getElementById('prod_categoria_id').value   = '';
    document.getElementById('prod_unidad').value         = 'UND';
    document.getElementById('prod_precio_compra').value  = '0.00';
    document.getElementById('prod_precio_venta').value   = '0.00';
    document.getElementById('prod_stock_minimo').value   = '5';
    document.getElementById('prod_descripcion').value    = '';
    document.getElementById('alertaProducto').innerHTML  = '';
    document.getElementById('codigoPreview').textContent = '...';
}
</script>
@endpush
