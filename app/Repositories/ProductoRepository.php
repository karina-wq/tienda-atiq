<?php

namespace App\Repositories;

use App\Models\Producto;
use App\Repositories\Interfaces\ProductoRepositoryInterface;

class ProductoRepository implements ProductoRepositoryInterface
{
    public function __construct(protected Producto $model) {}

    public function todos()
    {
        return $this->model
            ->with('categoria')
            ->orderBy('nombre')
            ->paginate(15);
    }

    public function activos()
    {
        return $this->model
            ->activos()
            ->with('categoria')
            ->orderBy('nombre')
            ->get();
    }

    public function buscarPorId(int $id)
    {
        return $this->model->with('categoria')->findOrFail($id);
    }

    public function buscarPorCodigo(string $codigo)
    {
        return $this->model->where('codigo', $codigo)->first();
    }

    public function buscarParaPOS(string $termino)
    {
        return $this->model
            ->activos()
            ->where(function ($q) use ($termino) {
                $q->where('nombre', 'ilike', "%{$termino}%")
                  ->orWhere('codigo', 'ilike', "%{$termino}%");
            })
            ->where('stock', '>', 0)
            ->with('categoria')
            ->limit(10)
            ->get();
    }

    public function crear(array $datos)
    {
        return $this->model->create($datos);
    }

    public function actualizar(int $id, array $datos)
    {
        $producto = $this->buscarPorId($id);
        $producto->update($datos);
        return $producto;
    }

    public function eliminar(int $id)
    {
        $producto = $this->buscarPorId($id);
        return $producto->delete();
    }

    public function actualizarStock(int $id, int $cantidad)
    {
        return $this->model
            ->where('id', $id)
            ->increment('stock', $cantidad);
    }

    public function conStockBajo()
    {
        return $this->model
            ->activos()
            ->conStockBajo()
            ->with('categoria')
            ->orderBy('stock')
            ->get();
    }
}
