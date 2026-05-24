@extends('layouts.app')

@section('titulo', 'Inventario General')
@section('encabezado', 'Inventario General')
@section('descripcion', 'Productos registrados en el sistema de inventario y ventas.')

@section('contenido')

    @php
        $rol = session('usuario.rol');
    @endphp

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200 mb-4">
    <form method="GET" action="{{ route('inventario.index') }}"
          class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

        <div>
            <label class="block font-semibold mb-2">Categoría</label>
            <select name="id_categoria" class="w-full border rounded-lg p-2">
                <option value="">Todas las categorías</option>

                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id_categoria }}"
                        {{ request('id_categoria') == $categoria->id_categoria ? 'selected' : '' }}>
                        {{ $categoria->nombre_categoria }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-2">Proveedor</label>
            <select name="id_proveedor" class="w-full border rounded-lg p-2">
                <option value="">Todos los proveedores</option>

                @foreach ($proveedores as $proveedor)
                    <option value="{{ $proveedor->id_proveedor }}"
                        {{ request('id_proveedor') == $proveedor->id_proveedor ? 'selected' : '' }}>
                        {{ $proveedor->nombre_proveedor }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-2">Estado</label>
            <select name="estado" class="w-full border rounded-lg p-2">
                <option value="">Todos</option>
                <option value="A" {{ request('estado') == 'A' ? 'selected' : '' }}>
                    Activos
                </option>
                <option value="I" {{ request('estado') == 'I' ? 'selected' : '' }}>
                    Inactivos
                </option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit"
                    class="bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700">
                Filtrar
            </button>

            <a href="{{ route('inventario.index') }}"
               class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                Limpiar
            </a>
        </div>

    </form>
</div>

    @if ($rol === 'ADMINISTRADOR')
        <div class="mb-4">
            <a href="{{ route('inventario.create') }}"
               class="inline-block bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700">
                Nuevo producto
            </a>
        </div>
    @endif

    <div class="bg-white shadow-md border border-gray-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Producto</th>
                        <th class="p-3 text-left">Categoría</th>
                        <th class="p-3 text-left">Proveedor</th>
                        <th class="p-3 text-right">Precio Compra</th>
                        <th class="p-3 text-right">Precio Venta</th>
                        <th class="p-3 text-center">Stock</th>
                        <th class="p-3 text-center">Mínimo</th>
                        <th class="p-3 text-center">Estado</th>

                        @if ($rol === 'ADMINISTRADOR')
                            <th class="p-3 text-center">Acciones</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @foreach ($productos as $producto)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $producto->id_producto }}</td>
                            <td class="p-3 font-semibold">{{ $producto->nombre_producto }}</td>
                            <td class="p-3">{{ $producto->nombre_categoria }}</td>
                            <td class="p-3">{{ $producto->nombre_proveedor }}</td>
                            <td class="p-3 text-right">₡{{ number_format($producto->precio_compra, 2) }}</td>
                            <td class="p-3 text-right">₡{{ number_format($producto->precio_venta, 2) }}</td>
                            <td class="p-3 text-center">{{ $producto->stock }}</td>
                            <td class="p-3 text-center">{{ $producto->stock_minimo }}</td>

                            <td class="p-3 text-center">
                                @if ($producto->estado == 'A')
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                                        Activo
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm">
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            @if ($rol === 'ADMINISTRADOR')
                                <td class="p-3 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('inventario.edit', $producto->id_producto) }}"
                                           class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                            Editar
                                        </a>

                                        @if ($producto->estado == 'A')
                                            <form action="{{ route('inventario.destroy', $producto->id_producto) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('¿Seguro que desea desactivar este producto?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                                                    Desactivar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection