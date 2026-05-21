@extends('layouts.app')

@section('titulo', 'Nueva compra')
@section('encabezado', 'Nueva compra')
@section('descripcion', 'Formulario para registrar una compra y aumentar inventario automáticamente.')

@section('contenido')

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow max-w-3xl">
        <form action="{{ route('compras.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold mb-2">Proveedor</label>
                <select name="id_proveedor" class="w-full border rounded-lg p-3" required>
                    <option value="">Seleccione un proveedor</option>
                    @foreach ($proveedores as $proveedor)
                        <option value="{{ $proveedor->id_proveedor }}"
                            {{ old('id_proveedor') == $proveedor->id_proveedor ? 'selected' : '' }}>
                            {{ $proveedor->nombre_proveedor }}
                        </option>
                    @endforeach
                </select>

                @error('id_proveedor')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">Producto</label>
                <select name="id_producto" class="w-full border rounded-lg p-3" required>
                    <option value="">Seleccione un producto</option>
                    @foreach ($productos as $producto)
                        <option value="{{ $producto->id_producto }}"
                            {{ old('id_producto') == $producto->id_producto ? 'selected' : '' }}>
                            {{ $producto->nombre_producto }}
                            - Precio compra actual: ₡{{ number_format($producto->precio_compra, 2) }}
                            - Stock: {{ $producto->stock }}
                        </option>
                    @endforeach
                </select>

                @error('id_producto')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

                <div>
                    <label class="block font-semibold mb-2">Cantidad</label>
                    <input type="number"
                           name="cantidad"
                           value="{{ old('cantidad') }}"
                           min="1"
                           class="w-full border rounded-lg p-3"
                           required>

                    @error('cantidad')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold mb-2">Precio unitario</label>
                    <input type="number"
                           name="precio_unitario"
                           value="{{ old('precio_unitario') }}"
                           min="0"
                           step="0.01"
                           class="w-full border rounded-lg p-3"
                           required>

                    @error('precio_unitario')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Registrar compra
                </button>

                <a href="{{ route('compras.index') }}"
                   class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

@endsection