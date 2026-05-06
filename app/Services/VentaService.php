<?php

namespace App\Services;

use App\Models\Venta;
use App\Models\VentaDetalle;
use App\Models\Producto;
use App\Repositories\VentaRepository;
use Illuminate\Support\Facades\DB;

class VentaService
{
    public function __construct(
        protected VentaRepository $ventaRepository,
        protected StockService    $stockService,
    ) {}

    public function registrar(array $datos, array $items): Venta
    {
        return DB::transaction(function () use ($datos, $items) {

            // Validar stock antes de procesar
            foreach ($items as $item) {
                $producto = Producto::findOrFail($item['producto_id']);
                if ($producto->stock < $item['cantidad']) {
                    throw new \Exception(
                        "Stock insuficiente para '{$producto->nombre}'. Disponible: {$producto->stock}"
                    );
                }
            }

            // Calcular totales
            $subtotalBruto = collect($items)->sum(
                fn($i) => $i['cantidad'] * $i['precio_venta']
            );
            $descuento = $datos['descuento'] ?? 0;
            $subtotal  = $subtotalBruto - $descuento;
            $igv       = round($subtotal * 0.18, 2);
            $total     = $subtotal;  // precio con IGV incluido (precio de venta ya incluye IGV)

            $montoPagado = $datos['monto_pagado'] ?? $total;
            $vuelto      = max(0, $montoPagado - $total);

            // Generar número de comprobante
            $numeroComprobante = $this->ventaRepository->generarNumeroComprobante(
                $datos['tipo_comprobante'] ?? 'BOLETA'
            );

            // Crear venta
            $venta = $this->ventaRepository->crear([
                'cliente_id'         => $datos['cliente_id'],
                'numero_comprobante' => $numeroComprobante,
                'tipo_comprobante'   => $datos['tipo_comprobante'] ?? 'BOLETA',
                'fecha'              => now()->toDateString(),
                'subtotal'           => $subtotal,
                'igv'                => $igv,
                'descuento'          => $descuento,
                'total'              => $total,
                'metodo_pago'        => $datos['metodo_pago'] ?? 'EFECTIVO',
                'monto_pagado'       => $montoPagado,
                'vuelto'             => $vuelto,
                'estado'             => 'COMPLETADA',
                'observaciones'      => $datos['observaciones'] ?? null,
            ]);

            // Registrar detalles y descontar stock
            foreach ($items as $item) {
                $producto = Producto::findOrFail($item['producto_id']);

                VentaDetalle::create([
                    'venta_id'        => $venta->id,
                    'producto_id'     => $producto->id,
                    'producto_nombre' => $producto->nombre,
                    'cantidad'        => $item['cantidad'],
                    'precio_compra'   => $producto->precio_compra, // snapshot
                    'precio_venta'    => $item['precio_venta'],    // snapshot
                    'descuento'       => $item['descuento'] ?? 0,
                    'subtotal'        => $item['cantidad'] * $item['precio_venta'],
                ]);

                // Descontar stock
                $this->stockService->registrarSalida(
                    productoId:    $producto->id,
                    cantidad:      $item['cantidad'],
                    origen:        'VENTA',
                    origenId:      $venta->id,
                    costoUnitario: $producto->precio_compra,
                );
            }

            return $venta->load(['cliente', 'detalles']);
        });
    }

    public function anular(int $ventaId): Venta
    {
        return DB::transaction(function () use ($ventaId) {
            $venta = $this->ventaRepository->buscarPorId($ventaId);

            if ($venta->estado === 'ANULADA') {
                throw new \Exception('Esta venta ya está anulada.');
            }

            // Revertir stock
            foreach ($venta->detalles as $detalle) {
                $this->stockService->registrarEntrada(
                    productoId:    $detalle->producto_id,
                    cantidad:      $detalle->cantidad,
                    origen:        'ANULACION_VENTA',
                    origenId:      $venta->id,
                    costoUnitario: $detalle->precio_compra,
                    observacion:   "Anulación de venta #{$venta->numero_comprobante}",
                );
            }

            $venta->update(['estado' => 'ANULADA']);
            return $venta;
        });
    }
}
