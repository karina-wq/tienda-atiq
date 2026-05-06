@extends('layouts.app')

@section('title', 'Nueva Venta — POS')

@push('styles')
<style>
    .producto-card {
        cursor: pointer;
        transition: all 0.15s;
        border: 2px solid transparent;
    }
    .producto-card:hover {
        border-color: #0d6efd;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13,110,253,.15) !important;
    }
    .carrito-item { border-bottom: 1px solid #f0f2f5; }
    .carrito-item:last-child { border-bottom: none; }
    #panel-carrito {
        position: sticky;
        top: 70px;
        max-height: calc(100vh - 90px);
        display: flex;
        flex-direction: column;
    }
    #lista-carrito {
        overflow-y: auto;
        flex: 1;
    }
    .qty-btn { width: 28px; height: 28px; padding: 0;
               display:inline-flex; align-items:center; justify-content:center; }
</style>
@endpush

@section('content')

<form action="{{ route('ventas.store') }}" method="POST" id="formVenta">
@csrf

<div class="row g-3">

    {{-- ========== PANEL IZQUIERDO: PRODUCTOS ========== --}}
    <div class="col-lg-7">

        {{-- Buscador --}}
        <div class="card shadow-sm mb-3">
            <div class="card-body py-2">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="buscarProducto"
                           class="form-control border-start-0 ps-0"
                           placeholder="Buscar producto por nombre o código..."
                           autocomplete="off">
                    <select id="filtroCat" class="form-select" style="max-width:180px">
                        <option value="">Todas las categorías</option>
                        @foreach($productos->pluck('categoria')->unique('id')->filter() as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Grid de productos --}}
        <div class="row g-2" id="gridProductos">
            @foreach($productos as $prod)
            <div class="col-6 col-md-4 col-xl-3 producto-item"
                 data-nombre="{{ strtolower($prod->nombre) }}"
                 data-codigo="{{ strtolower($prod->codigo) }}"
                 data-cat="{{ $prod->categoria_id }}">
                <div class="card shadow-sm producto-card h-100"
                     onclick="agregarAlCarrito({{ $prod->id }}, '{{ addslashes($prod->nombre) }}', {{ $prod->precio_venta }}, {{ $prod->stock }}, '{{ $prod->unidad }}')">
                    <div class="card-body p-2 text-center">
                        <div class="mb-1">
                            @if($prod->stock == 0)
                                <span class="badge bg-danger">Sin stock</span>
                            @elseif($prod->tiene_stock_bajo)
                                <span class="badge bg-warning text-dark">Stock: {{ $prod->stock }}</span>
                            @else
                                <span class="badge bg-success">Stock: {{ $prod->stock }}</span>
                            @endif
                        </div>
                        <div class="fw-medium small lh-sm mb-1" style="min-height:32px">
                            {{ Str::limit($prod->nombre, 30) }}
                        </div>
                        <div class="text-muted" style="font-size:0.7rem">
                            <code>{{ $prod->codigo }}</code>
                        </div>
                        <div class="fw-bold text-success mt-1">
                            S/ {{ number_format($prod->precio_venta, 2) }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>

    {{-- ========== PANEL DERECHO: CARRITO ========== --}}
    <div class="col-lg-5">
        <div id="panel-carrito">

            {{-- Cabecera venta --}}
            <div class="card shadow-sm mb-2">
                <div class="card-body py-2">
                    <div class="row g-2">
                        <div class="col-7">
                            <label class="form-label mb-1 small">Cliente</label>
                            <select name="cliente_id" class="form-select form-select-sm">
                                @foreach($clientes as $cli)
                                    <option value="{{ $cli->id }}"
                                        {{ $cli->numero_documento === '00000000' ? 'selected' : '' }}>
                                        {{ $cli->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-5">
                            <label class="form-label mb-1 small">Comprobante</label>
                            <select name="tipo_comprobante" class="form-select form-select-sm">
                                <option value="TICKET">TICKET</option>
                                <option value="BOLETA">BOLETA</option>
                                <option value="FACTURA">FACTURA</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lista del carrito --}}
            <div class="card shadow-sm mb-2 flex-fill" style="min-height:0">
                <div class="card-header bg-white py-2 d-flex justify-content-between">
                    <span class="fw-semibold small">
                        <i class="bi bi-cart3 me-1 text-primary"></i>
                        Carrito (<span id="cant-items">0</span> items)
                    </span>
                    <button type="button" class="btn btn-sm btn-outline-danger py-0"
                            onclick="limpiarCarrito()">
                        <i class="bi bi-trash me-1"></i>Limpiar
                    </button>
                </div>
                <div id="lista-carrito" class="card-body p-0" style="max-height:300px; overflow-y:auto">
                    <div id="carrito-vacio" class="text-center text-muted py-4">
                        <i class="bi bi-cart-x fs-3 d-block mb-1"></i>
                        <small>El carrito está vacío</small>
                    </div>
                    <div id="carrito-items"></div>
                </div>
            </div>

            {{-- Totales + Pago --}}
            <div class="card shadow-sm">
                <div class="card-body">

                    {{-- Descuento --}}
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="text-muted small flex-fill">Descuento (S/):</span>
                        <input type="number" name="descuento" id="inputDescuento"
                               class="form-control form-control-sm text-end"
                               style="width:100px"
                               value="0" min="0" step="0.01"
                               oninput="recalcularTotales()">
                    </div>

                    <hr class="my-2">

                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small">Subtotal:</span>
                        <span id="txt-subtotal" class="small">S/ 0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted small">Descuento:</span>
                        <span id="txt-descuento" class="small text-danger">- S/ 0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">TOTAL:</span>
                        <span id="txt-total" class="fw-bold fs-5 text-success">S/ 0.00</span>
                    </div>

                    {{-- Método de pago --}}
                    <div class="mb-2">
                        <label class="form-label small mb-1">Método de Pago</label>
                        <div class="d-flex gap-1 flex-wrap">
                            @foreach(['EFECTIVO','TARJETA','YAPE','PLIN'] as $mp)
                            <div class="form-check form-check-inline m-0">
                                <input class="form-check-input" type="radio"
                                       name="metodo_pago" id="mp_{{ $mp }}"
                                       value="{{ $mp }}"
                                       {{ $mp === 'EFECTIVO' ? 'checked' : '' }}
                                       onchange="toggleEfectivo()">
                                <label class="form-check-label small" for="mp_{{ $mp }}">
                                    {{ $mp }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Monto pagado / vuelto --}}
                    <div id="seccionEfectivo">
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small mb-1">Monto Recibido</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">S/</span>
                                    <input type="number" name="monto_pagado" id="inputMontoPagado"
                                           class="form-control text-end fw-bold"
                                           value="0" min="0" step="0.01"
                                           oninput="calcularVuelto()">
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label small mb-1">Vuelto</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">S/</span>
                                    <input type="text" id="inputVuelto"
                                           class="form-control text-end fw-bold text-success bg-light"
                                           readonly value="0.00">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="seccionOtroPago" class="d-none mb-2">
                        <input type="hidden" name="monto_pagado" id="montoPagadoHidden">
                    </div>

                    <button type="submit" id="btnCobrar"
                            class="btn btn-success w-100 py-2 fw-bold"
                            disabled>
                        <i class="bi bi-check-circle me-2"></i>
                        COBRAR — <span id="btn-total">S/ 0.00</span>
                    </button>

                </div>
            </div>

        </div>
    </div>

</div>
</form>

@endsection

@push('scripts')
<script>
let carrito = {};

// ── AGREGAR AL CARRITO ──────────────────────────────────────────
function agregarAlCarrito(id, nombre, precio, stock, unidad) {
    if (stock <= 0) {
        mostrarAlerta('Sin stock disponible para: ' + nombre, 'warning');
        return;
    }

    if (carrito[id]) {
        if (carrito[id].cantidad >= stock) {
            mostrarAlerta('Stock máximo alcanzado: ' + stock + ' ' + unidad, 'warning');
            return;
        }
        carrito[id].cantidad++;
    } else {
        carrito[id] = { id, nombre, precio, stock, unidad, cantidad: 1 };
    }

    renderCarrito();
}

// ── RENDER CARRITO ──────────────────────────────────────────────
function renderCarrito() {
    const items    = Object.values(carrito);
    const contenedor = document.getElementById('carrito-items');
    const vacio    = document.getElementById('carrito-vacio');

    document.getElementById('cant-items').textContent = items.length;

    if (items.length === 0) {
        contenedor.innerHTML = '';
        vacio.classList.remove('d-none');
        document.getElementById('btnCobrar').disabled = true;
        recalcularTotales();
        return;
    }

    vacio.classList.add('d-none');
    document.getElementById('btnCobrar').disabled = false;

    contenedor.innerHTML = items.map((item, i) => `
        <div class="carrito-item px-3 py-2">
            <input type="hidden" name="items[${i}][producto_id]"  value="${item.id}">
            <input type="hidden" name="items[${i}][cantidad]"      value="${item.cantidad}">
            <input type="hidden" name="items[${i}][precio_venta]"  value="${item.precio}">
            <input type="hidden" name="items[${i}][descuento]"     value="0">

            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-fill me-2">
                    <div class="fw-medium small lh-sm">${item.nombre}</div>
                    <div class="text-muted" style="font-size:0.75rem">
                        S/ ${item.precio.toFixed(2)} × ${item.cantidad} = 
                        <strong class="text-dark">S/ ${(item.precio * item.cantidad).toFixed(2)}</strong>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <button type="button" class="btn btn-outline-secondary qty-btn"
                            onclick="cambiarCantidad(${item.id}, -1)">
                        <i class="bi bi-dash"></i>
                    </button>
                    <span class="fw-bold" style="min-width:24px;text-align:center">
                        ${item.cantidad}
                    </span>
                    <button type="button" class="btn btn-outline-secondary qty-btn"
                            onclick="cambiarCantidad(${item.id}, 1)">
                        <i class="bi bi-plus"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger qty-btn ms-1"
                            onclick="eliminarItem(${item.id})">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');

    recalcularTotales();
}

// ── CAMBIAR CANTIDAD ────────────────────────────────────────────
function cambiarCantidad(id, delta) {
    if (!carrito[id]) return;
    const nueva = carrito[id].cantidad + delta;
    if (nueva <= 0) {
        eliminarItem(id);
        return;
    }
    if (nueva > carrito[id].stock) {
        mostrarAlerta('Stock máximo: ' + carrito[id].stock, 'warning');
        return;
    }
    carrito[id].cantidad = nueva;
    renderCarrito();
}

function eliminarItem(id) {
    delete carrito[id];
    renderCarrito();
}

function limpiarCarrito() {
    carrito = {};
    renderCarrito();
}

// ── TOTALES ─────────────────────────────────────────────────────
function recalcularTotales() {
    const items    = Object.values(carrito);
    const subtotal = items.reduce((s, i) => s + i.precio * i.cantidad, 0);
    const descuento = parseFloat(document.getElementById('inputDescuento').value) || 0;
    const total    = Math.max(0, subtotal - descuento);

    document.getElementById('txt-subtotal').textContent  = 'S/ ' + subtotal.toFixed(2);
    document.getElementById('txt-descuento').textContent = '- S/ ' + descuento.toFixed(2);
    document.getElementById('txt-total').textContent     = 'S/ ' + total.toFixed(2);
    document.getElementById('btn-total').textContent     = 'S/ ' + total.toFixed(2);

    calcularVuelto();
}

function calcularVuelto() {
    const total     = totalActual();
    const recibido  = parseFloat(document.getElementById('inputMontoPagado')?.value) || 0;
    const vuelto    = Math.max(0, recibido - total);
    const elVuelto  = document.getElementById('inputVuelto');
    if (elVuelto) elVuelto.value = vuelto.toFixed(2);

    // Actualizar hidden para otros métodos
    const hidden = document.getElementById('montoPagadoHidden');
    if (hidden) hidden.value = total.toFixed(2);
}

function totalActual() {
    const items    = Object.values(carrito);
    const subtotal = items.reduce((s, i) => s + i.precio * i.cantidad, 0);
    const descuento = parseFloat(document.getElementById('inputDescuento').value) || 0;
    return Math.max(0, subtotal - descuento);
}

function toggleEfectivo() {
    const metodo = document.querySelector('input[name="metodo_pago"]:checked').value;
    const secEf  = document.getElementById('seccionEfectivo');
    const secOt  = document.getElementById('seccionOtroPago');
    if (metodo === 'EFECTIVO') {
        secEf.classList.remove('d-none');
        secOt.classList.add('d-none');
    } else {
        secEf.classList.add('d-none');
        secOt.classList.remove('d-none');
        document.getElementById('montoPagadoHidden').value = totalActual().toFixed(2);
    }
}

// ── FILTROS ─────────────────────────────────────────────────────
document.getElementById('buscarProducto').addEventListener('input', filtrar);
document.getElementById('filtroCat').addEventListener('change', filtrar);

function filtrar() {
    const texto = document.getElementById('buscarProducto').value.toLowerCase();
    const cat   = document.getElementById('filtroCat').value;

    document.querySelectorAll('.producto-item').forEach(el => {
        const matchTexto = !texto ||
            el.dataset.nombre.includes(texto) ||
            el.dataset.codigo.includes(texto);
        const matchCat = !cat || el.dataset.cat === cat;
        el.style.display = (matchTexto && matchCat) ? '' : 'none';
    });
}

// ── VALIDAR SUBMIT ──────────────────────────────────────────────
document.getElementById('formVenta').addEventListener('submit', function (e) {
    if (Object.keys(carrito).length === 0) {
        e.preventDefault();
        mostrarAlerta('El carrito está vacío.', 'danger');
        return;
    }
    const metodo   = document.querySelector('input[name="metodo_pago"]:checked').value;
    const total    = totalActual();
    const recibido = metodo === 'EFECTIVO'
        ? parseFloat(document.getElementById('inputMontoPagado').value) || 0
        : total;

    if (metodo === 'EFECTIVO' && recibido < total) {
        e.preventDefault();
        mostrarAlerta('El monto recibido es menor al total.', 'danger');
        return;
    }

    // Actualizar monto_pagado para EFECTIVO
    if (metodo === 'EFECTIVO') {
        document.getElementById('inputMontoPagado').name = 'monto_pagado';
    }
});

// ── ALERTA TOAST ────────────────────────────────────────────────
function mostrarAlerta(msg, tipo = 'info') {
    const el = document.createElement('div');
    el.className = `alert alert-${tipo} alert-dismissible position-fixed
                    bottom-0 end-0 m-3 shadow`;
    el.style.zIndex = 9999;
    el.innerHTML = `${msg}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 3000);
}
</script>
@endpush