<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Compra;
use App\Models\Producto;
use App\Models\VentaDetalle;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function ventas(Request $request)
    {
        $inicio = $request->get('inicio', now()->startOfMonth()->toDateString());
        $fin    = $request->get('fin', now()->toDateString());

        $ventas = Venta::completadas()
            ->delPeriodo($inicio, $fin)
            ->with(['cliente', 'detalles'])
            ->orderBy('fecha', 'desc')
            ->get();

        $totales = [
            'ventas'    => $ventas->sum('total'),
            'descuentos'=> $ventas->sum('descuento'),
            'cantidad'  => $ventas->count(),
            'utilidad'  => $ventas->sum(fn($v) => $v->utilidad),
        ];

        return view('reportes.ventas', compact('ventas', 'totales', 'inicio', 'fin'));
    }

    public function stock(Request $request)
    {
        $productos = Producto::with('categoria')
            ->orderBy('nombre')
            ->get()
            ->map(function ($p) {
                $p->valor_stock = $p->stock * $p->precio_compra;
                return $p;
            });

        $totales = [
            'productos'   => $productos->count(),
            'valor_total' => $productos->sum('valor_stock'),
            'stock_bajo'  => $productos->where('tiene_stock_bajo', true)->count(),
            'sin_stock'   => $productos->where('stock', 0)->count(),
        ];

        return view('reportes.stock', compact('productos', 'totales'));
    }

    public function utilidades(Request $request)
    {
        $inicio = $request->get('inicio', now()->startOfMonth()->toDateString());
        $fin    = $request->get('fin', now()->toDateString());

        $detalles = VentaDetalle::whereHas('venta', function ($q) use ($inicio, $fin) {
                $q->completadas()->delPeriodo($inicio, $fin);
            })
            ->with(['producto', 'venta'])
            ->get()
            ->groupBy('producto_id')
            ->map(function ($items) {
                $primero = $items->first();
                return [
                    'producto'       => $primero->producto_nombre,
                    'cantidad'       => $items->sum('cantidad'),
                    'ingresos'       => $items->sum('subtotal'),
                    'costo'          => $items->sum(fn($i) => $i->precio_compra * $i->cantidad),
                    'utilidad'       => $items->sum(fn($i) => $i->utilidad),
                ];
            })
            ->sortByDesc('utilidad')
            ->values();

        $totales = [
            'ingresos' => $detalles->sum('ingresos'),
            'costos'   => $detalles->sum('costo'),
            'utilidad' => $detalles->sum('utilidad'),
        ];

        return view('reportes.utilidades', compact('detalles', 'totales', 'inicio', 'fin'));
    }
}
