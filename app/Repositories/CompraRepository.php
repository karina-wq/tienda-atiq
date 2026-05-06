<?php

namespace App\Repositories;

use App\Models\Compra;

class CompraRepository
{
    public function __construct(protected Compra $model) {}

    public function todas()
    {
        return $this->model
            ->with(['proveedor', 'detalles'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function buscarPorId(int $id)
    {
        return $this->model
            ->with(['proveedor', 'detalles.producto'])
            ->findOrFail($id);
    }

    public function crear(array $datos)
    {
        return $this->model->create($datos);
    }

    public function delPeriodo(string $inicio, string $fin)
    {
        return $this->model
            ->completadas()
            ->delPeriodo($inicio, $fin)
            ->with('proveedor')
            ->orderBy('fecha', 'desc')
            ->get();
    }
}
