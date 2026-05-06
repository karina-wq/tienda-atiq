<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoStock extends Model
{
    protected $table = 'movimientos_stock';

    protected $fillable = [
        'producto_id',
        'tipo',
        'origen',
        'origen_id',
        'cantidad',
        'stock_anterior',
        'stock_nuevo',
        'costo_unitario',
        'observacion',
    ];

    protected $casts = [
        'costo_unitario' => 'decimal:2',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function scopeEntradas($query)
    {
        return $query->where('tipo', 'ENTRADA');
    }

    public function scopeSalidas($query)
    {
        return $query->where('tipo', 'SALIDA');
    }
}
