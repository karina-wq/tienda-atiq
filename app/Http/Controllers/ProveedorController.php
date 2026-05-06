<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Http\Requests\Proveedor\StoreProveedorRequest;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::orderBy('razon_social')->paginate(15);
        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(StoreProveedorRequest $request)
    {
        Proveedor::create($request->validated());
        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor registrado correctamente.');
    }

    public function edit(Proveedor $proveedor)
    {
        return view('proveedores.edit', compact('proveedor'));
    }

    public function update(StoreProveedorRequest $request, Proveedor $proveedor)
    {
        $proveedor->update($request->validated());
        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedor)
    {
        if ($proveedor->compras()->count() > 0) {
            return redirect()->route('proveedores.index')
                ->with('error', 'No se puede eliminar: tiene compras registradas.');
        }
        $proveedor->delete();
        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado.');
    }
}
