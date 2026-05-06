<?php

namespace App\Http\Requests\Compra;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompraRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'proveedor_id'          => 'required|exists:proveedores,id',
            'tipo_comprobante'      => 'required|in:FACTURA,BOLETA,TICKET',
            'numero_comprobante'    => 'nullable|string|max:50',
            'fecha'                 => 'required|date',
            'observaciones'         => 'nullable|string',
            'detalles'              => 'required|array|min:1',
            'detalles.*.producto_id'   => 'required|exists:productos,id',
            'detalles.*.cantidad'      => 'required|integer|min:1',
            'detalles.*.precio_compra' => 'required|numeric|min:0.01',
        ];
    }

    public function messages(): array
    {
        return [
            'detalles.required'              => 'Debe agregar al menos un producto.',
            'detalles.*.producto_id.required' => 'Seleccione un producto válido.',
            'detalles.*.cantidad.min'         => 'La cantidad mínima es 1.',
            'detalles.*.precio_compra.min'    => 'El precio debe ser mayor a cero.',
        ];
    }
}
