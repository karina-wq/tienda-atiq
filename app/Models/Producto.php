<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use SoftDeletes;

    protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        'codigo',
        'nombre',
        'descripcion',
        'unidad',
        'precio_compra',
        'precio_venta',
        'stock',
        'stock_minimo',
        'activo',
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta'  => 'decimal:2',
        'activo'        => 'boolean',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function compraDetalles()
    {
        return $this->hasMany(CompraDetalle::class);
    }

    public function ventaDetalles()
    {
        return $this->hasMany(VentaDetalle::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoStock::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeConStockBajo($query)
    {
        return $query->whereColumn('stock', '<=', 'stock_minimo');
    }

    public function getMargenAttribute()
    {
        if ($this->precio_compra <= 0) return 0;
        return round((($this->precio_venta - $this->precio_compra) / $this->precio_compra) * 100, 2);
    }

    public function getTieneStockBajoAttribute()
    {
        return $this->stock <= $this->stock_minimo;
    }
}
