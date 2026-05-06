<?php

namespace App\Services;

use App\Models\Compra;
use App\Models\CompraDetalle;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Repositories\CompraRepository;
use Illuminate\Support\Facades\DB;

class CompraService
{
    public function __construct(protected CompraRepository $compraRepo)
    {}

    public function registrar(array $data, array $detalles): Compra
    {
        DB::beginTransaction();
        try {
            // Calcular sin IGV
            $subtotal = 0;
            foreach ($detalles as $detalle) {
                $subtotal += $detalle['cantidad'] * $detalle['precio_compra'];
            }
            
            $compra = Compra::create([
                'proveedor_id' => $data['proveedor_id'],
                'tipo_comprobante' => $data['tipo_comprobante'] ?? 'FACTURA',
                'numero_comprobante' => $data['numero_comprobante'] ?? null,
                'fecha' => $data['fecha'],
                'subtotal' => $subtotal,
                'igv' => 0, // IGV en 0
                'total' => $subtotal, // Total = subtotal
                'estado' => 'COMPLETADA',
                'observaciones' => $data['observaciones'] ?? null,
            ]);
            
            foreach ($detalles as $item) {
                $producto = Producto::findOrFail($item['producto_id']);
                
                CompraDetalle::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $producto->id,
                    'producto_nombre' => $producto->nombre,
                    'cantidad' => $item['cantidad'],
                    'precio_compra' => $item['precio_compra'],
                    'subtotal' => $item['cantidad'] * $item['precio_compra'],
                ]);
                
                // Actualizar stock y precio de compra
                $producto->increment('stock', $item['cantidad']);
                $producto->precio_compra = $item['precio_compra'];
                $producto->save();
            }
            
            DB::commit();
            return $compra;
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception('Error al registrar compra: ' . $e->getMessage());
        }
    }
    
    public function anular(int $id): void
    {
        DB::beginTransaction();
        try {
            $compra = $this->compraRepo->buscarPorId($id);
            
            if ($compra->estado === 'ANULADA') {
                throw new \Exception('La compra ya está anulada');
            }
            
            // Revertir stock
            foreach ($compra->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->decrement('stock', $detalle->cantidad);
                }
            }
            
            $compra->update(['estado' => 'ANULADA']);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception('Error al anular compra: ' . $e->getMessage());
        }
    }
}