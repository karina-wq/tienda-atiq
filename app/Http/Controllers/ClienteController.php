<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\Cliente\StoreClienteRequest;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('nombre')->paginate(15);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(StoreClienteRequest $request)
    {
        Cliente::create($request->validated());
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(StoreClienteRequest $request, Cliente $cliente)
    {
        $cliente->update($request->validated());
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        if ($cliente->ventas()->count() > 0) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se puede eliminar: tiene ventas registradas.');
        }
        $cliente->delete();
        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado.');
    }
}
