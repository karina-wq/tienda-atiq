<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TIENDA ATIQ') — TIENDA ATIQ</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-bg: #1e2a3a;
            --sidebar-hover: #2d3f55;
            --sidebar-active: #0d6efd;
            --topbar-height: 56px;
        }

        body { background-color: #f0f2f5; font-size: 0.9rem; }

        /* SIDEBAR */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: all 0.3s;
            overflow-y: auto;
        }

        #sidebar .sidebar-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 1.2rem;
            background: #111d2b;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            border-bottom: 1px solid #2d3f55;
        }

        #sidebar .nav-section {
            padding: 0.5rem 1rem 0.2rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6c8099;
            margin-top: 0.5rem;
        }

        #sidebar .nav-link {
            color: #adb9c7;
            padding: 0.6rem 1.2rem;
            border-radius: 0;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.2s;
            font-size: 0.875rem;
        }

        #sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        #sidebar .nav-link.active {
            background: var(--sidebar-active);
            color: #fff;
        }

        #sidebar .nav-link i { font-size: 1rem; width: 20px; }

        /* MAIN CONTENT */
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* TOPBAR */
        #topbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            position: sticky;
            top: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            gap: 1rem;
        }

        .page-content { padding: 1.5rem; flex: 1; }

        /* CARDS */
        .stat-card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-card .stat-icon {
            width: 50px; height: 50px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }

        /* TABLES */
        .table th { font-size: 0.8rem; text-transform: uppercase;
                    letter-spacing: 0.5px; color: #6c757d; }
        .table td { vertical-align: middle; }

        /* BADGES */
        .badge-stock-bajo { background-color: #fff3cd; color: #856404; }
        .badge-sin-stock  { background-color: #f8d7da; color: #842029; }

        /* ALERTS */
        .alert { border-radius: 8px; }

        /* FORMS */
        .form-label { font-weight: 500; font-size: 0.85rem; }
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.15);
        }

        @yield('styles')
    </style>

    @stack('styles')
</head>
<body>

<!-- SIDEBAR -->
<nav id="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-shop me-2 text-primary"></i>
        TIENDA ATIQ
    </div>

    <ul class="nav flex-column mt-1">

        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>

        <li><div class="nav-section">Ventas</div></li>

        <li class="nav-item">
            <a href="{{ route('ventas.create') }}"
               class="nav-link {{ request()->routeIs('ventas.create') ? 'active' : '' }}">
                <i class="bi bi-cart-plus"></i> Nueva Venta (POS)
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('ventas.index') }}"
               class="nav-link {{ request()->routeIs('ventas.index') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Historial Ventas
            </a>
        </li>

        <li><div class="nav-section">Inventario</div></li>

        <li class="nav-item">
            <a href="{{ route('productos.index') }}"
               class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> Productos
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('compras.index') }}"
               class="nav-link {{ request()->routeIs('compras.*') ? 'active' : '' }}">
                <i class="bi bi-truck"></i> Compras
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('categorias.index') }}"
               class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Categorías
            </a>
        </li>

        <li><div class="nav-section">Personas</div></li>

        <li class="nav-item">
            <a href="{{ route('clientes.index') }}"
               class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Clientes
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('proveedores.index') }}"
               class="nav-link {{ request()->routeIs('proveedores.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Proveedores
            </a>
        </li>

        <li><div class="nav-section">Reportes</div></li>

        <li class="nav-item">
            <a href="{{ route('reportes.ventas') }}"
               class="nav-link {{ request()->routeIs('reportes.ventas') ? 'active' : '' }}">
                <i class="bi bi-bar-chart"></i> Reporte Ventas
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('reportes.utilidades') }}"
               class="nav-link {{ request()->routeIs('reportes.utilidades') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i> Utilidades
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('reportes.stock') }}"
               class="nav-link {{ request()->routeIs('reportes.stock') ? 'active' : '' }}">
                <i class="bi bi-clipboard-data"></i> Reporte Stock
            </a>
        </li>

    </ul>
{{-- Al final del sidebar, antes del cierre </nav> o </aside> --}}
<div class="mt-auto p-3">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
            class="btn btn-danger w-100 d-flex align-items-center gap-2">
            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
        </button>
    </form>
</div>

</nav>

<!-- MAIN -->
<div id="main-content">

    <!-- TOPBAR -->
    <div id="topbar">
        <div class="fw-semibold text-dark">@yield('title', 'Dashboard')</div>
        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="text-muted small">
                <i class="bi bi-calendar3"></i>
                {{ now()->isoFormat('dddd D [de] MMMM, YYYY') }}
            </span>
            <a href="{{ route('ventas.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Nueva Venta
            </a>
        </div>
    </div>

    <!-- ALERTS -->
    <div class="page-content pb-0 pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Corrige los siguientes errores:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>


    <!-- PAGE CONTENT -->
    <div class="page-content">
        @yield('content')
    </div>


    <footer class="text-center text-muted py-3 small border-top bg-white mt-auto">
        TIENDA ATIQ &copy; {{ date('Y') }} — Todos los derechos reservados
    </footer>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>
