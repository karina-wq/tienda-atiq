<?php

namespace App\Http\Requests\Proveedor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProveedorRequest extends FormRequest
{
    public function authorize(): bool 
    { 
        return true; 
    }

    public function rules(): array
    {
        $proveedor = $this->route('proveedor');
        
        return [
            'razon_social' => 'required|string|max:200',
            'ruc' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('proveedores', 'ruc')->ignore($proveedor?->id)
            ],
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable|string|max:255',
            'contacto' => 'nullable|string|max:100',
        ];
    }
    
    public function messages(): array
    {
        return [
            'razon_social.required' => 'La razón social es obligatoria',
            'ruc.unique' => 'Este RUC ya está registrado',
            'email.email' => 'El correo electrónico no es válido',
        ];
    }
}