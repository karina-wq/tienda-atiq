@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Importar Compras Masivas</h3>
                    <a href="{{ route('compras.index') }}" class="btn btn-secondary">Volver</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('compras.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Archivo Excel (xlsx, xls, csv)</label>
                            <input type="file" name="archivo" class="form-control" required accept=".xlsx,.xls,.csv">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Importar</button>
                    </form>
                    
                    <div class="mt-5">
                        <h4>Formato del Excel requerido:</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr><th>Columna</th><th>Requerido</th><th>Ejemplo</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td>ruc_proveedor</td><td>Sí</td><td>20123456789</td></tr>
                                    <tr><td>proveedor</td><td>No</td><td>PROVEEDOR SAC</td></tr>
                                    <tr><td>telefono</td><td>No</td><td>999999999</td></tr>
                                    <tr><td>direccion</td><td>No</td><td>Av. Principal 123</td></tr>
                                    <tr><td>email</td><td>No</td><td>proveedor@mail.com</td></tr>
                                    <tr><td>categoria</td><td>No</td><td>ELECTRONICA</td></tr>
                                    <tr><td>codigo_producto</td><td>No</td><td>PROD001</td></tr>
                                    <tr><td>producto</td><td>Sí</td><td>Producto Nuevo</td></tr>
                                    <tr><td>precio_compra</td><td>Sí</td><td>100.00</td></tr>
                                    <tr><td>precio_venta</td><td>No</td><td>130.00</td></tr>
                                    <tr><td>cantidad</td><td>Sí</td><td>10</td></tr>
                                    <tr><td>tipo_comprobante</td><td>No</td><td>FACTURA</td></tr>
                                    <tr><td>num_comprobante</td><td>No</td><td>F001-1</td></tr>
                                    <tr><td>fecha</td><td>No</td><td>2026-05-06</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ asset('sample/compras_import_sample.xlsx') }}" class="btn btn-info">Descargar ejemplo Excel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection