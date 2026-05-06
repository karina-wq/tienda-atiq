<?php

namespace App\Imports;

use App\Models\Compra;
use App\Models\CompraDetalle;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Categoria;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class ComprasMasivasImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                // Validar datos mínimos requeridos
                if (empty($row['ruc_proveedor']) || empty($row['producto']) || empty($row['cantidad']) || empty($row['precio_compra'])) {
                    throw new \Exception("Faltan datos requeridos en fila: " . json_encode($row));
                }
                
                // 1. Crear o obtener proveedor
                $proveedor = Proveedor::firstOrCreate(
                    ['ruc' => $row['ruc_proveedor']],
                    [
                        'razon_social' => $row['proveedor'] ?? 'Proveedor sin nombre',
                        'telefono' => $row['telefono'] ?? null,
                        'direccion' => $row['direccion'] ?? null,
                        'email' => $row['email'] ?? null,
                        'activo' => true,
                    ]
                );
                
                // 2. Crear o obtener categoría
                $nombreCategoria = $row['categoria'] ?? 'GENERAL';
                $categoria = Categoria::firstOrCreate(
                    ['nombre' => $nombreCategoria],
                    ['descripcion' => $nombreCategoria]
                );
                
                // 3. Crear o obtener producto
                $codigoProducto = $row['codigo_producto'] ?? uniqid('PROD_');
                $producto = Producto::firstOrCreate(
                    ['codigo' => $codigoProducto],
                    [
                        'nombre' => $row['producto'],
                        'categoria_id' => $categoria->id,
                        'stock' => 0,
                        'precio_compra' => $row['precio_compra'],
                        'precio_venta' => $row['precio_venta'] ?? ($row['precio_compra'] * 1.3),
                        'activo' => true,
                    ]
                );
                
                // Si el producto ya existe y viene stock, actualizar
                if ($producto->wasRecentlyCreated === false) {
                    $producto->precio_compra = $row['precio_compra'];
                    if (isset($row['precio_venta'])) {
                        $producto->precio_venta = $row['precio_venta'];
                    }
                }
                
                // 4. Crear compra
                $subtotal = $row['cantidad'] * $row['precio_compra'];
                $compra = Compra::create([
                    'proveedor_id' => $proveedor->id,
                    'tipo_comprobante' => $row['tipo_comprobante'] ?? 'FACTURA',
                    'numero_comprobante' => $row['num_comprobante'] ?? 'S/N',
                    'fecha' => $row['fecha'] ?? now()->toDateString(),
                    'subtotal' => $subtotal,
                    'igv' => 0,
                    'total' => $subtotal,
                    'estado' => 'COMPLETADA',
                    'observaciones' => $row['observaciones'] ?? 'Importado masivamente',
                ]);
                
                // 5. Crear detalle de compra
                CompraDetalle::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $producto->id,
                    'producto_nombre' => $producto->nombre,
                    'cantidad' => $row['cantidad'],
                    'precio_compra' => $row['precio_compra'],
                    'subtotal' => $subtotal,
                ]);
                
                // 6. Actualizar stock del producto
                $producto->increment('stock', $row['cantidad']);
                $producto->save();
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception('Error en importación: ' . $e->getMessage());
        }
    }
}