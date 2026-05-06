<?php

namespace App\Http\Requests\Producto;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'categoria_id'  => 'required|exists:categorias,id',
            'codigo'        => 'required|string|max:50|unique:productos,codigo',
            'nombre'        => 'required|string|max:200',
            'descripcion'   => 'nullable|string|max:500',
            'unidad'        => 'required|in:UND,KG,LT,MT,CJA,PAQ',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta'  => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'stock_minimo'  => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'categoria_id.required' => 'Seleccione una categoría.',
            'categoria_id.exists'   => 'La categoría no existe.',
            'codigo.unique'         => 'El código ya está registrado.',
            'nombre.required'       => 'El nombre del producto es obligatorio.',
            'precio_venta.min'      => 'El precio de venta no puede ser negativo.',
        ];
    }
}
