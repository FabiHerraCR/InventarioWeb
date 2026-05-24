@extends('layouts.app')

@section('titulo', 'Inicio - Sistema de Inventario')
@section('encabezado', 'Panel principal')
@section('descripcion', 'Resumen general del sistema de gestión de inventario y ventas.')

@section('contenido')

    @php
        $rol = session('usuario.rol');
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- INVENTARIO -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Inventario</h3>
            <p class="text-gray-600 mt-2">
                Consulta productos, stock, categorías y proveedores.
            </p>
            <a href="{{ route('inventario.index') }}"
               class="inline-block mt-4 text-blue-600 hover:underline">
                Ver inventario
            </a>
        </div>

        <!-- CLIENTES -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Clientes</h3>
            <p class="text-gray-600 mt-2">
                Consulta los clientes registrados en el sistema.
            </p>
            <a href="{{ route('clientes.index') }}"
               class="inline-block mt-4 text-blue-600 hover:underline">
                Ver clientes
            </a>
        </div>

        <!-- VENTAS -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Ventas</h3>
            <p class="text-gray-600 mt-2">
                Consulta las ventas registradas en el sistema.
            </p>
            <a href="{{ route('ventas.index') }}"
               class="inline-block mt-4 text-blue-600 hover:underline">
                Ver ventas
            </a>
        </div>

        <!-- SOLO ADMINISTRADOR -->
        @if ($rol === 'ADMINISTRADOR')

            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Proveedores</h3>
                <p class="text-gray-600 mt-2">
                    Consulta proveedores registrados para compras e inventario.
                </p>
                <a href="{{ route('proveedores.index') }}"
                   class="inline-block mt-4 text-blue-600 hover:underline">
                    Ver proveedores
                </a>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Compras</h3>
                <p class="text-gray-600 mt-2">
                    Consulta las compras registradas a proveedores.
                </p>
                <a href="{{ route('compras.index') }}"
                   class="inline-block mt-4 text-blue-600 hover:underline">
                    Ver compras
                </a>
            </div>

        @endif

        <!-- REPORTES PARA AMBOS ROLES -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Reportes</h3>

            @if ($rol === 'ADMINISTRADOR')
                <p class="text-gray-600 mt-2">
                    Reportes generales del sistema, ventas, inventario y gráficos.
                </p>
            @else
                <p class="text-gray-600 mt-2">
                    Reportes de sus ventas registradas en el sistema.
                </p>
            @endif

            <a href="{{ route('reportes.index') }}"
               class="inline-block mt-4 text-blue-600 hover:underline">
                Ver reportes
            </a>
        </div>

    </div>

@endsection