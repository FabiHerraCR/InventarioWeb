@extends('layouts.app')

@section('titulo', 'Nueva venta')
@section('encabezado', 'Nueva venta')
@section('descripcion', 'Formulario para registrar una venta y descontar inventario automáticamente.')

@section('contenido')

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow max-w-3xl">
        <form action="{{ route('ventas.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold mb-2">Cliente</label>
                <select name="id_cliente" class="w-full border rounded-lg p-3" required>
                    <option value="">Seleccione un cliente</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id_cliente }}"
                            {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}>
                            {{ $cliente->nombre_cliente }}
                        </option>
                    @endforeach
                </select>

                @error('id_cliente')
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
                            - Precio: ₡{{ number_format($producto->precio_venta, 2) }}
                            - Stock: {{ $producto->stock }}
                        </option>
                    @endforeach
                </select>

                @error('id_producto')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
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

            <div class="flex gap-2">
                <button type="submit"
                        class="bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Registrar venta
                </button>

                <a href="{{ route('ventas.index') }}"
                   class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

@endsection