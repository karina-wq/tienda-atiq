<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Repositories\Interfaces\ProductoRepositoryInterface;
use App\Http\Requests\Producto\StoreProductoRequest;
use App\Http\Requests\Producto\UpdateProductoRequest;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function __construct(
        protected ProductoRepositoryInterface $productoRepo
    ) {}

    public function index()
    {
        $productos  = $this->productoRepo->todos();
        $categorias = Categoria::activas()->get();
        return view('productos.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::activas()->get();
        return view('productos.create', compact('categorias'));
    }

    public function store(StoreProductoRequest $request)
    {
        $this->productoRepo->crear($request->validated());
        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function show(int $id)
    {
        $producto = $this->productoRepo->buscarPorId($id);
        $movimientos = $producto->movimientos()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('productos.show', compact('producto', 'movimientos'));
    }

    public function edit(int $id)
    {
        $producto   = $this->productoRepo->buscarPorId($id);
        $categorias = Categoria::activas()->get();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(UpdateProductoRequest $request, int $id)
    {
        $this->productoRepo->actualizar($id, $request->validated());
        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(int $id)
    {
        $this->productoRepo->eliminar($id);
        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    // Para búsqueda AJAX en el POS
    public function buscar(Request $request)
    {
        $termino   = $request->get('q', '');
        $productos = $this->productoRepo->buscarParaPOS($termino);
        return response()->json($productos);
    }
}
