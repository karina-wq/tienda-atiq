<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'tipo_documento',
        'numero_documento',
        'telefono',
        'email',
        'direccion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function getTotalComprasAttribute()
    {
        return $this->ventas()->where('estado', 'COMPLETADA')->sum('total');
    }
}
