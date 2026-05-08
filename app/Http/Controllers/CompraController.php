<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\Categoria;
use App\Repositories\CompraRepository;
use App\Services\CompraService;
use App\Http\Requests\Compra\StoreCompraRequest;

class CompraController extends Controller
{
    public function __construct(
        protected CompraRepository $compraRepo,
        protected CompraService    $compraService,
    ) {}

    public function index()
    {
        $compras = $this->compraRepo->todas();
        return view('compras.index', compact('compras'));
    }

    public function create()
    {
        $proveedores = Proveedor::activos()->orderBy('razon_social')->get();
        $productos   = Producto::activos()->orderBy('nombre')->get();
        $categorias  = Categoria::activas()->orderBy('nombre')->get(); // Para modal nuevo producto
        return view('compras.create', compact('proveedores', 'productos', 'categorias'));
    }

    public function store(StoreCompraRequest $request)
    {
        try {
            $compra = $this->compraService->registrar(
                $request->except('detalles'),
                $request->input('detalles')
            );
            return redirect()->route('compras.show', $compra->id)
                ->with('success', "Compra #{$compra->id} registrada correctamente.");
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $compra = $this->compraRepo->buscarPorId($id);
        return view('compras.show', compact('compra'));
    }

    public function anular(int $id)
    {
        try {
            $this->compraService->anular($id);
            return redirect()->route('compras.index')
                ->with('success', 'Compra anulada correctamente.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
