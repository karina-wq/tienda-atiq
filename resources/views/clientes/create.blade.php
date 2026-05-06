@extends('layouts.app')

@section('title', 'Nuevo Cliente')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Nuevo Cliente</h5>
        <small class="text-muted">Complete el formulario</small>
    </div>
    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf
            <div class="row g-3">

                <div class="col-md-8">
                    <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                    <input type="text" name="nombre"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre') }}" placeholder="Nombre del cliente">
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipo Documento</label>
                    <select name="tipo_documento" class="form-select">
                        @foreach(['DNI','RUC','CE'] as $tipo)
                            <option value="{{ $tipo }}" {{ old('tipo_documento','DNI') == $tipo ? 'selected':'' }}>
                                {{ $tipo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Número de Documento</label>
                    <input type="text" name="numero_documento"
                           class="form-control @error('numero_documento') is-invalid @enderror"
                           value="{{ old('numero_documento') }}" placeholder="00000000">
                    @error('numero_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="{{ old('telefono') }}" placeholder="999 999 999">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}" placeholder="correo@ejemplo.com">
                </div>

                <div class="col-12">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control"
                           value="{{ old('direccion') }}" placeholder="Dirección completa">
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1"></i> Guardar Cliente
                </button>
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@endsection