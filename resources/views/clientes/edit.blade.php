@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-bold">Editar Cliente</h5>
        <small class="text-muted">{{ $cliente->nombre }}</small>
    </div>
    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row g-3">

                <div class="col-md-8">
                    <label class="form-label">Nombre completo <span class="text-danger">*</span></label>
                    <input type="text" name="nombre"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $cliente->nombre) }}">
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipo Documento</label>
                    <select name="tipo_documento" class="form-select">
                        @foreach(['DNI','RUC','CE'] as $tipo)
                            <option value="{{ $tipo }}"
                                {{ old('tipo_documento', $cliente->tipo_documento) == $tipo ? 'selected':'' }}>
                                {{ $tipo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Número de Documento</label>
                    <input type="text" name="numero_documento"
                           class="form-control @error('numero_documento') is-invalid @enderror"
                           value="{{ old('numero_documento', $cliente->numero_documento) }}">
                    @error('numero_documento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="{{ old('telefono', $cliente->telefono) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $cliente->email) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control"
                           value="{{ old('direccion', $cliente->direccion) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="activo" class="form-select">
                        <option value="1" {{ $cliente->activo ? 'selected':'' }}>Activo</option>
                        <option value="0" {{ !$cliente->activo ? 'selected':'' }}>Inactivo</option>
                    </select>
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1"></i> Actualizar Cliente
                </button>
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

@endsection