<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';

    protected $fillable = [
        'cliente_id',
        'numero_comprobante',
        'tipo_comprobante',
        'fecha',
        'subtotal',
        'igv',
        'descuento',
        'total',
        'metodo_pago',
        'monto_pagado',
        'vuelto',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha'       => 'date',
        'subtotal'    => 'decimal:2',
        'igv'         => 'decimal:2',
        'descuento'   => 'decimal:2',
        'total'       => 'decimal:2',
        'monto_pagado'=> 'decimal:2',
        'vuelto'      => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class);
    }

    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'COMPLETADA');
    }

    public function scopeDelPeriodo($query, $inicio, $fin)
    {
        return $query->whereBetween('fecha', [$inicio, $fin]);
    }

    public function getUtilidadAttribute()
    {
        return $this->detalles->sum(function ($detalle) {
            return ($detalle->precio_venta - $detalle->precio_compra) * $detalle->cantidad;
        });
    }
}
