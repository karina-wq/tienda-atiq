<?php

namespace App\Http\Requests\Venta;

use Illuminate\Foundation\Http\FormRequest;

class StoreVentaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'cliente_id'           => 'required|exists:clientes,id',
            'tipo_comprobante'     => 'required|in:BOLETA,FACTURA,TICKET',
            'metodo_pago'          => 'required|in:EFECTIVO,TARJETA,YAPE,PLIN',
            'descuento'            => 'nullable|numeric|min:0',
            'monto_pagado'         => 'required|numeric|min:0',
            'observaciones'        => 'nullable|string',
            'items'                => 'required|array|min:1',
            'items.*.producto_id'  => 'required|exists:productos,id',
            'items.*.cantidad'     => 'required|integer|min:1',
            'items.*.precio_venta' => 'required|numeric|min:0.01',
            'items.*.descuento'    => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'              => 'El carrito está vacío.',
            'items.*.producto_id.exists'  => 'Producto no encontrado.',
            'items.*.cantidad.min'        => 'La cantidad mínima es 1.',
            'monto_pagado.min'            => 'El monto pagado no puede ser negativo.',
        ];
    }
}
