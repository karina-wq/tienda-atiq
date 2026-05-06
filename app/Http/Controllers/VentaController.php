<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Producto;
use App\Repositories\VentaRepository;
use App\Services\VentaService;
use App\Http\Requests\Venta\StoreVentaRequest;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function __construct(
        protected VentaRepository $ventaRepo,
        protected VentaService    $ventaService,
    ) {}

    public function index()
    {
        $ventas = $this->ventaRepo->todas();
        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $clientes  = Cliente::activos()->orderBy('nombre')->get();
        $productos = Producto::activos()->with('categoria')->orderBy('nombre')->get();
        return view('ventas.create', compact('clientes', 'productos'));
    }

    public function store(StoreVentaRequest $request)
    {
        try {
            $venta = $this->ventaService->registrar(
                $request->except('items'),
                $request->input('items')
            );
            return redirect()->route('ventas.show', $venta->id)
                ->with('success', "Venta {$venta->numero_comprobante} registrada.");
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $venta = $this->ventaRepo->buscarPorId($id);
        return view('ventas.show', compact('venta'));
    }

    public function anular(int $id)
    {
        try {
            $this->ventaService->anular($id);
            return redirect()->route('ventas.index')
                ->with('success', 'Venta anulada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

public function ticketPdf(int $venta)
{
    $ventaObj = $this->ventaRepo->buscarPorId($venta);
    $pdf = \PDF::loadView('ventas.ticket_pdf', ['venta' => $ventaObj]);
    return $pdf->download('ticket-'.$venta.'.pdf');
}
public function ticket(int $id)
{
    $venta = $this->ventaRepo->buscarPorId($id);
    return view('ventas.ticket', compact('venta'));
}

    // AJAX: buscar producto para el POS
    public function buscarProducto(Request $request)
    {
        $termino = $request->get('q', '');
        $productos = Producto::activos()
            ->where(function ($q) use ($termino) {
                $q->where('nombre', 'ilike', "%{$termino}%")
                  ->orWhere('codigo', 'ilike', "%{$termino}%");
            })
            ->where('stock', '>', 0)
            ->with('categoria')
            ->limit(10)
            ->get(['id','codigo','nombre','precio_venta','stock','unidad']);

        return response()->json($productos);
    }
}
