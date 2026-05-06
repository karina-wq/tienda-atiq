<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    protected $table = 'venta_detalles';

    protected $fillable = [
        'venta_id',
        'producto_id',
        'producto_nombre',
        'cantidad',
        'precio_compra',
        'precio_venta',
        'descuento',
        'subtotal',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta'  => 'decimal:2',
        'descuento'     => 'decimal:2',
        'subtotal'      => 'decimal:2',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function getUtilidadAttribute()
    {
        return ($this->precio_venta - $this->precio_compra) * $this->cantidad;
    }
}
