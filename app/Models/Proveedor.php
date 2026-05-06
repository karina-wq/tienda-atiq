<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use SoftDeletes;

    protected $table = 'proveedores';

    protected $fillable = [
        'razon_social',
        'ruc',
        'telefono',
        'email',
        'direccion',
        'contacto',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function compras()
    {
        return $this->hasMany(Compra::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
