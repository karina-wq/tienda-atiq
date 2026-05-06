<?php

namespace App\Repositories\Interfaces;

interface ProductoRepositoryInterface
{
    public function todos();
    public function activos();
    public function buscarPorId(int $id);
    public function buscarPorCodigo(string $codigo);
    public function buscarParaPOS(string $termino);
    public function crear(array $datos);
    public function actualizar(int $id, array $datos);
    public function eliminar(int $id);
    public function actualizarStock(int $id, int $cantidad);
    public function conStockBajo();
}
