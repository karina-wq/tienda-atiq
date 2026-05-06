<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compras';

    protected $fillable = [
        'proveedor_id',
        'numero_comprobante',
        'tipo_comprobante',
        'fecha',
        'subtotal',
        'igv',
        'total',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha'    => 'date',
        'subtotal' => 'decimal:2',
        'igv'      => 'decimal:2',
        'total'    => 'decimal:2',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function detalles()
    {
        return $this->hasMany(CompraDetalle::class);
    }

    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'COMPLETADA');
    }

    public function scopeDelPeriodo($query, $inicio, $fin)
    {
        return $query->whereBetween('fecha', [$inicio, $fin]);
    }
}
