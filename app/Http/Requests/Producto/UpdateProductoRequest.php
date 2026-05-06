<?php

namespace App\Http\Requests\Producto;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('producto');
        return [
            'categoria_id'  => 'required|exists:categorias,id',
            'codigo'        => "required|string|max:50|unique:productos,codigo,{$id}",
            'nombre'        => 'required|string|max:200',
            'descripcion'   => 'nullable|string|max:500',
            'unidad'        => 'required|in:UND,KG,LT,MT,CJA,PAQ',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta'  => 'required|numeric|min:0',
            'stock_minimo'  => 'required|integer|min:0',
        ];
    }
}
