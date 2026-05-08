<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\Auth\LoginController;

// Autenticación
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Productos
    Route::get('productos/buscar',         [ProductoController::class, 'buscar'])->name('productos.buscar');
    Route::get('productos/proximo-codigo', [ProductoController::class, 'proximoCodigo'])->name('productos.proximo-codigo');
    Route::post('productos/ajax',          [ProductoController::class, 'storeAjax'])->name('productos.store-ajax');
    Route::resource('productos', ProductoController::class);

    // Categorías
    Route::resource('categorias', CategoriaController::class)->except(['show', 'create', 'edit']);

    // Clientes
    Route::post('clientes/ajax', [ClienteController::class, 'storeAjax'])->name('clientes.store-ajax');
    Route::resource('clientes', ClienteController::class)->except(['show']);

    // Proveedores
    Route::post('proveedores/ajax', [ProveedorController::class, 'storeAjax'])->name('proveedores.store-ajax');
    Route::resource('proveedores', ProveedorController::class)->except(['show']);

    // Compras
    Route::resource('compras', CompraController::class)->except(['edit', 'update', 'destroy']);
    Route::patch('compras/{id}/anular', [CompraController::class, 'anular'])->name('compras.anular');

    // Ventas
    Route::get('ventas/buscar-producto',   [VentaController::class, 'buscarProducto'])->name('ventas.buscar-producto');
    Route::get('ventas/{id}/ticket',       [VentaController::class, 'ticket'])->name('ventas.ticket');
    Route::get('ventas/{id}/ticket-pdf',   [VentaController::class, 'ticketPdf'])->name('ventas.ticket-pdf');
    Route::resource('ventas', VentaController::class)->except(['edit', 'update', 'destroy']);
    Route::patch('ventas/{id}/anular',     [VentaController::class, 'anular'])->name('ventas.anular');

    // Reportes
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('ventas',     [ReporteController::class, 'ventas'])->name('ventas');
        Route::get('stock',      [ReporteController::class, 'stock'])->name('stock');
        Route::get('utilidades', [ReporteController::class, 'utilidades'])->name('utilidades');
    });

});
