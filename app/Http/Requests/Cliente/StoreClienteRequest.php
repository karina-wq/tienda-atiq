<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('cliente');
        return [
            'nombre'           => 'required|string|max:200',
            'tipo_documento'   => 'required|in:DNI,RUC,CE',
            'numero_documento' => "nullable|string|max:20|unique:clientes,numero_documento,{$id}",
            'telefono'         => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:100',
            'direccion'        => 'nullable|string|max:255',
        ];
    }
}
