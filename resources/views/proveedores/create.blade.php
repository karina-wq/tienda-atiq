@extends('layouts.app')

@section('title', 'Nuevo Proveedor')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Nuevo Proveedor</h5>
        <small class="text-muted">Complete el formulario</small>
    </div>
    <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('proveedores.store') }}" method="POST">
            @csrf
            <div class="row g-3">

                <div class="col-md-8">
                    <label class="form-label">Razón Social <span class="text-danger">*</span></label>
                    <input type="text" name="razon_social"
                           class="form-control @error('razon_social') is-invalid @enderror"
                           value="{{ old('razon_social') }}" placeholder="Nombre de la empresa">
                    @error('razon_social')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">RUC</label>
                    <input type="text" name="ruc"
                           class="form-control @error('ruc') is-invalid @enderror"
                           value="{{ old('ruc') }}" placeholder="20000000000">
                    @error('ruc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Persona de Contacto</label>
                    <input type="text" name="contacto" class="form-control"
                           value="{{ old('contacto') }}" placeholder="Nombre del contacto">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="{{ old('telefono') }}" placeholder="999 999 999">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}" placeholder="proveedor@email.com">
                </div>

                <div class="col-12">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control"
                           value="{{ old('direccion') }}" placeholder="Dirección completa">
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1"></i> Guardar Proveedor
                </button>
                <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@endsection