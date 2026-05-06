<?php

namespace App\Repositories;

use App\Models\Venta;

class VentaRepository
{
    public function __construct(protected Venta $model) {}

    public function todas()
    {
        return $this->model
            ->with(['cliente', 'detalles'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function buscarPorId(int $id)
    {
        return $this->model
            ->with(['cliente', 'detalles.producto'])
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
            ->with(['cliente', 'detalles'])
            ->orderBy('fecha', 'desc')
            ->get();
    }

    public function totalDelDia(string $fecha)
    {
        return $this->model
            ->completadas()
            ->whereDate('fecha', $fecha)
            ->sum('total');
    }

    public function generarNumeroComprobante(string $tipo): string
    {
        $prefijos = [
            'BOLETA'  => 'B001',
            'FACTURA' => 'F001',
            'TICKET'  => 'T001',
        ];

        $prefijo = $prefijos[$tipo] ?? 'T001';

        $ultimo = $this->model
            ->where('numero_comprobante', 'like', "{$prefijo}-%")
            ->orderBy('id', 'desc')
            ->value('numero_comprobante');

        $numero = $ultimo
            ? (int) substr($ultimo, -8) + 1
            : 1;

        return $prefijo . '-' . str_pad($numero, 8, '0', STR_PAD_LEFT);
    }
}
