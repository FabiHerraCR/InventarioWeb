<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Sistema de Inventario')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="min-h-screen flex">

        <!-- MENU LATERAL -->
        <aside class="w-64 bg-gray-900 text-white min-h-screen p-5">
            <h1 class="text-2xl font-bold mb-8">
                Inventario Web
            </h1>

@if (session('usuario'))
    <div class="mb-6 text-sm bg-gray-800 p-3 rounded">
        <p class="font-semibold">{{ session('usuario.nombre') }}</p>
        <p class="text-gray-300">{{ session('usuario.rol') }}</p>
    </div>
@endif

@php
    $rol = session('usuario.rol');
@endphp

<nav class="space-y-2">
    <a href="{{ route('dashboard') }}"
       class="block px-4 py-2 rounded hover:bg-gray-700">
        Inicio
    </a>

    <a href="{{ route('inventario.index') }}"
       class="block px-4 py-2 rounded hover:bg-gray-700">
        Inventario
    </a>

    @if ($rol === 'ADMINISTRADOR')
    <a href="{{ route('categorias.index') }}"
       class="block px-4 py-2 rounded hover:bg-gray-700">
        Categorías
    </a>
    @endif

    <a href="{{ route('movimientos.index') }}"
   class="block px-4 py-2 rounded hover:bg-gray-700">
    Movimientos
    </a>

    <a href="{{ route('clientes.index') }}"
       class="block px-4 py-2 rounded hover:bg-gray-700">
        Clientes
    </a>

@if ($rol === 'ADMINISTRADOR')
    <a href="{{ route('proveedores.index') }}"
       class="block px-4 py-2 rounded hover:bg-gray-700">
        Proveedores
    </a>
@endif

<a href="{{ route('ventas.index') }}"
   class="block px-4 py-2 rounded hover:bg-gray-700">
    Ventas
</a>

@if ($rol === 'ADMINISTRADOR')
    <a href="{{ route('compras.index') }}"
       class="block px-4 py-2 rounded hover:bg-gray-700">
        Compras
    </a>
@endif

<a href="{{ route('reportes.index') }}"
   class="block px-4 py-2 rounded hover:bg-gray-700">
    Reportes
</a>

<form action="{{ route('logout') }}" method="POST" class="mt-8">
    @csrf

    <button
        type="submit"
        class="w-full text-left px-4 py-2 rounded bg-red-600 hover:bg-red-700 text-white">
        Cerrar sesión
    </button>
</form>

            </nav>
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="flex-1 p-8">
            <div class="mb-6">
                <h2 class="text-3xl font-bold text-gray-900">
                    @yield('encabezado')
                </h2>

                <p class="text-gray-600 mt-1">
                    @yield('descripcion')
                </p>
            </div>

            @if (session('error'))
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

            @yield('contenido')
        </main>

    </div>

    @stack('scripts')

</body>
</html>