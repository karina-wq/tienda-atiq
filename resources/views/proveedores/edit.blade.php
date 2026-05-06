@extends('layouts.app')

@section('title', 'Editar Proveedor')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Editar Proveedor</h5>
        <small class="text-muted">{{ $proveedor->razon_social }}</small>
    </div>
    <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('proveedores.update', $proveedor->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">

                <div class="col-md-8">
                    <label class="form-label">Razón Social <span class="text-danger">*</span></label>
                    <input type="text" name="razon_social"
                           class="form-control @error('razon_social') is-invalid @enderror"
                           value="{{ old('razon_social', $proveedor->razon_social) }}">
                    @error('razon_social')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">RUC</label>
                    <input type="text" name="ruc"
                           class="form-control @error('ruc') is-invalid @enderror"
                           value="{{ old('ruc', $proveedor->ruc) }}">
                    @error('ruc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Persona de Contacto</label>
                    <input type="text" name="contacto" class="form-control"
                           value="{{ old('contacto', $proveedor->contacto) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="{{ old('telefono', $proveedor->telefono) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $proveedor->email) }}">
                </div>

                <div class="col-md-9">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control"
                           value="{{ old('direccion', $proveedor->direccion) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="activo" class="form-select">
                        <option value="1" {{ $proveedor->activo ? 'selected':'' }}>Activo</option>
                        <option value="0" {{ !$proveedor->activo ? 'selected':'' }}>Inactivo</option>
                    </select>
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1"></i> Actualizar Proveedor
                </button>
                <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@endsection