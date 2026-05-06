<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { width: 100%; max-width: 400px; border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        .card-header { background: #2d3748; color: white; text-align: center; padding: 2rem; border-radius: 12px 12px 0 0; }
        .btn-primary { background: #2d3748; border: none; }
        .btn-primary:hover { background: #1a202c; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">🏪 SISTEMA DE VENTA ATIQ</h4>
            <small>Ingresa tus credenciales</small>
        </div>
        <div class="card-body p-4">
            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Correo electrónico</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}" placeholder="admin@pos.com" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Contraseña</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="••••••••" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Recordarme</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">
                    Iniciar Sesión
                </button>
            </form>
        </div>
    </div>
</body>
</html>
