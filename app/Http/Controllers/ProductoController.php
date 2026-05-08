<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
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
        $categorias  = Categoria::activas()->get();
        $proximoCodigo = $this->generarCodigo();
        return view('productos.create', compact('categorias', 'proximoCodigo'));
    }

    public function store(StoreProductoRequest $request)
    {
        $data = $request->validated();

        // Si no se envió código (o está vacío), generarlo automáticamente
        if (empty($data['codigo'])) {
            $data['codigo'] = $this->generarCodigo();
        }

        $this->productoRepo->crear($data);
        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function show(int $id)
    {
        $producto    = $this->productoRepo->buscarPorId($id);
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

    /**
     * Devuelve el próximo código disponible (GET /productos/proximo-codigo).
     */
    public function proximoCodigo()
    {
        return response()->json(['codigo' => $this->generarCodigo()]);
    }

    /**
     * Crea un producto vía AJAX desde el modal de compras.
     * El código se genera automáticamente y se devuelve al frontend.
     */
    public function storeAjax(Request $request)
    {
        $validated = $request->validate([
            'nombre'        => 'required|string|max:200',
            'categoria_id'  => 'required|exists:categorias,id',
            'unidad'        => 'nullable|string|max:20',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta'  => 'required|numeric|min:0',
            'stock_minimo'  => 'nullable|integer|min:0',
            'descripcion'   => 'nullable|string|max:500',
        ]);

        $validated['codigo']       = $this->generarCodigo();
        $validated['stock']        = 0;
        $validated['unidad']       = $validated['unidad'] ?? 'UND';
        $validated['stock_minimo'] = $validated['stock_minimo'] ?? 5;

        $producto = Producto::create($validated);

        return response()->json([
            'success'       => true,
            'id'            => $producto->id,
            'codigo'        => $producto->codigo,
            'nombre'        => $producto->nombre,
            'precio_compra' => (float) $producto->precio_compra,
        ]);
    }

    /**
     * Genera el próximo código correlativo con formato PROD-00001.
     * Usa PHP para el ordenamiento, compatible con MySQL, SQLite y PostgreSQL.
     */
    private function generarCodigo(): string
    {
        $maximo = Producto::withTrashed()
            ->where('codigo', 'like', 'PROD-%')
            ->pluck('codigo')
            ->map(fn($codigo) => (int) ltrim(substr($codigo, 5), '0') ?: 0)
            ->max();   // devuelve null si no hay ninguno

        $siguiente = ($maximo ?? 0) + 1;

        return 'PROD-' . str_pad($siguiente, 5, '0', STR_PAD_LEFT);
    }
}
