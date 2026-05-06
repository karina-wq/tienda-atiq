<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use App\Models\Compra;
use App\Models\Cliente;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = now()->toDateString();

        $data = [
            'ventas_hoy'        => Venta::completadas()->whereDate('fecha', $hoy)->sum('total'),
            'ventas_mes'        => Venta::completadas()->whereMonth('fecha', now()->month)->sum('total'),
            'total_ventas_hoy'  => Venta::completadas()->whereDate('fecha', $hoy)->count(),
            'productos_total'   => Producto::activos()->count(),
            'stock_bajo'        => Producto::activos()->conStockBajo()->count(),
            'clientes_total'    => Cliente::count(),
            'ultimas_ventas'    => Venta::with('cliente')
                                    ->completadas()
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get(),
            'productos_sin_stock' => Producto::activos()
                                    ->where('stock', 0)
                                    ->with('categoria')
                                    ->limit(5)
                                    ->get(),
            'productos_stock_bajo' => Producto::activos()
                                    ->conStockBajo()
                                    ->with('categoria')
                                    ->orderBy('stock')
                                    ->limit(5)
                                    ->get(),
        ];

        return view('dashboard', $data);
    }
}
