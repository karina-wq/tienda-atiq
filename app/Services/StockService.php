<?php

namespace App\Services;

use App\Models\MovimientoStock;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function registrarEntrada(
        int    $productoId,
        int    $cantidad,
        string $origen,
        int    $origenId,
        float  $costoUnitario = 0,
        string $observacion = ''
    ): MovimientoStock {
        return DB::transaction(function () use (
            $productoId, $cantidad, $origen, $origenId, $costoUnitario, $observacion
        ) {
            $producto = Producto::lockForUpdate()->findOrFail($productoId);

            $stockAnterior = $producto->stock;
            $stockNuevo    = $stockAnterior + $cantidad;

            $producto->update(['stock' => $stockNuevo]);

            return MovimientoStock::create([
                'producto_id'    => $productoId,
                'tipo'           => 'ENTRADA',
                'origen'         => $origen,
                'origen_id'      => $origenId,
                'cantidad'       => $cantidad,
                'stock_anterior' => $stockAnterior,
                'stock_nuevo'    => $stockNuevo,
                'costo_unitario' => $costoUnitario,
                'observacion'    => $observacion,
            ]);
        });
    }

    public function registrarSalida(
        int    $productoId,
        int    $cantidad,
        string $origen,
        int    $origenId,
        float  $costoUnitario = 0,
        string $observacion = ''
    ): MovimientoStock {
        return DB::transaction(function () use (
            $productoId, $cantidad, $origen, $origenId, $costoUnitario, $observacion
        ) {
            $producto = Producto::lockForUpdate()->findOrFail($productoId);

            if ($producto->stock < $cantidad) {
                throw new \Exception(
                    "Stock insuficiente para '{$producto->nombre}'. Disponible: {$producto->stock}"
                );
            }

            $stockAnterior = $producto->stock;
            $stockNuevo    = $stockAnterior - $cantidad;

            $producto->update(['stock' => $stockNuevo]);

            return MovimientoStock::create([
                'producto_id'    => $productoId,
                'tipo'           => 'SALIDA',
                'origen'         => $origen,
                'origen_id'      => $origenId,
                'cantidad'       => $cantidad,
                'stock_anterior' => $stockAnterior,
                'stock_nuevo'    => $stockNuevo,
                'costo_unitario' => $costoUnitario,
                'observacion'    => $observacion,
            ]);
        });
    }

    public function ajustarStock(
        int    $productoId,
        int    $stockNuevo,
        string $observacion = 'Ajuste manual'
    ): MovimientoStock {
        return DB::transaction(function () use ($productoId, $stockNuevo, $observacion) {
            $producto = Producto::lockForUpdate()->findOrFail($productoId);

            $stockAnterior = $producto->stock;
            $diferencia    = $stockNuevo - $stockAnterior;

            $producto->update(['stock' => $stockNuevo]);

            return MovimientoStock::create([
                'producto_id'    => $productoId,
                'tipo'           => 'AJUSTE',
                'origen'         => 'AJUSTE_MANUAL',
                'origen_id'      => null,
                'cantidad'       => abs($diferencia),
                'stock_anterior' => $stockAnterior,
                'stock_nuevo'    => $stockNuevo,
                'costo_unitario' => 0,
                'observacion'    => $observacion,
            ]);
        });
    }
}
