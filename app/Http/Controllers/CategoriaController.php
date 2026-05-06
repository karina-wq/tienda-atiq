<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::withCount('productos')->orderBy('nombre')->paginate(15);
        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100|unique:categorias,nombre',
            'descripcion' => 'nullable|string|max:255',
        ]);

        Categoria::create($request->only('nombre', 'descripcion'));
        return redirect()->route('categorias.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre'      => "required|string|max:100|unique:categorias,nombre,{$categoria->id}",
            'descripcion' => 'nullable|string|max:255',
        ]);

        $categoria->update($request->only('nombre', 'descripcion'));
        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada.');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->productos()->count() > 0) {
            return redirect()->route('categorias.index')
                ->with('error', 'No se puede eliminar: tiene productos asociados.');
        }
        $categoria->delete();
        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada.');
    }
}
