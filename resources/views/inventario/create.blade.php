@extends('layouts.app')

@section('titulo', 'Nuevo producto')
@section('encabezado', 'Nuevo producto')
@section('descripcion', 'Formulario para registrar un nuevo producto en el inventario.')

@section('contenido')

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

<div id="formCategoriaRapida" class="bg-white p-4 rounded-xl shadow mb-4 hidden">
    <h3 class="text-lg font-bold mb-3">Nueva categoría rápida</h3>

    <form action="{{ route('categorias.store') }}" method="POST" novalidate>
        @csrf
        <input type="hidden" name="volver" value="producto">

        <div class="mb-3">
            <label class="block font-semibold mb-2">Nombre de la categoría</label>
            <input type="text"
                   name="nombre_categoria"
                   class="w-full border rounded-lg p-3"
                   required>
        </div>

        <div class="mb-3">
            <label class="block font-semibold mb-2">Descripción</label>
            <textarea name="descripcion"
                      class="w-full border rounded-lg p-3"></textarea>
        </div>

        <div class="flex gap-2">
            <button type="submit"
                    class="bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700">
                Guardar categoría
            </button>

            <button type="button"
                    onclick="ocultarCategoriaRapida()"
                    class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                Cancelar
            </button>
        </div>
    </form>
</div>

<div id="formProveedorRapido" class="bg-white p-4 rounded-xl shadow mb-4 hidden">
    <h3 class="text-lg font-bold mb-3">Nuevo proveedor rápido</h3>

    <form action="{{ route('proveedores.store') }}" method="POST" novalidate>
        @csrf
        <input type="hidden" name="volver" value="producto">

        <div class="mb-3">
            <label class="block font-semibold mb-2">Nombre del proveedor</label>
            <input type="text"
                   name="nombre_proveedor"
                   class="w-full border rounded-lg p-3"
                   required>
        </div>

        <div class="mb-3">
            <label class="block font-semibold mb-2">Teléfono</label>
            <input type="text"
                   name="telefono"
                   class="w-full border rounded-lg p-3">
        </div>

        <div class="mb-3">
            <label class="block font-semibold mb-2">Correo</label>
            <input type="email"
                   name="correo"
                   class="w-full border rounded-lg p-3"
                   placeholder="correo@ejemplo.com">
        </div>

        <div class="mb-3">
            <label class="block font-semibold mb-2">Dirección</label>
            <textarea name="direccion"
                      class="w-full border rounded-lg p-3"></textarea>
        </div>

        <div class="flex gap-2">
            <button type="submit"
                    class="bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700">
                Guardar proveedor
            </button>

            <button type="button"
                    onclick="ocultarProveedorRapido()"
                    class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                Cancelar
            </button>
        </div>
    </form>
</div>

    <div class="bg-white p-6 rounded-xl shadow max-w-4xl">
        <form action="{{ route('inventario.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>

<div class="flex justify-between items-center mb-2">
    <label class="block font-semibold">Categoría</label>

<button type="button"
        onclick="mostrarCategoriaRapida()"
        class="text-blue-600 text-sm hover:underline">
    + Nueva categoría
</button>

</div>

                    <select name="id_categoria" class="w-full border rounded-lg p-3" required>
                        <option value="">Seleccione una categoría</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id_categoria }}"
                                {{ old('id_categoria') == $categoria->id_categoria ? 'selected' : '' }}>
                                {{ $categoria->nombre_categoria }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_categoria')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div>

<div class="flex justify-between items-center mb-2">
    <label class="block font-semibold">Proveedor</label>

<button type="button"
        onclick="mostrarProveedorRapido()"
        class="text-blue-600 text-sm hover:underline">
    + Nuevo proveedor
</button>

</div>
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

            </div>

            <div class="mt-4">
                <label class="block font-semibold mb-2">Nombre del producto</label>
                <input type="text" name="nombre_producto" value="{{ old('nombre_producto') }}"
                       class="w-full border rounded-lg p-3" required>
                @error('nombre_producto')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <label class="block font-semibold mb-2">Descripción</label>
                <textarea name="descripcion"
                          class="w-full border rounded-lg p-3">{{ old('descripcion') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">

                <div>
                    <label class="block font-semibold mb-2">Precio compra</label>
                    <input type="number" name="precio_compra" value="{{ old('precio_compra') }}"
                           class="w-full border rounded-lg p-3" min="0" step="0.01" required>
                    @error('precio_compra')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold mb-2">Precio venta</label>
                    <input type="number" name="precio_venta" value="{{ old('precio_venta') }}"
                           class="w-full border rounded-lg p-3" min="0" step="0.01" required>
                    @error('precio_venta')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold mb-2">Stock</label>
                    <input type="number" name="stock" value="{{ old('stock') }}"
                           class="w-full border rounded-lg p-3" min="0" required>
                    @error('stock')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold mb-2">Stock mínimo</label>
                    <input type="number" name="stock_minimo" value="{{ old('stock_minimo') }}"
                           class="w-full border rounded-lg p-3" min="0" required>
                    @error('stock_minimo')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex gap-2 mt-6">
                <button type="submit"
                        class="bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Guardar
                </button>

                <a href="{{ route('inventario.index') }}"
                   class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

<script>
    function mostrarCategoriaRapida() {
        document.getElementById('formCategoriaRapida').classList.remove('hidden');
        document.getElementById('formProveedorRapido').classList.add('hidden');
    }

    function ocultarCategoriaRapida() {
        document.getElementById('formCategoriaRapida').classList.add('hidden');
    }

    function mostrarProveedorRapido() {
        document.getElementById('formProveedorRapido').classList.remove('hidden');
        document.getElementById('formCategoriaRapida').classList.add('hidden');
    }

    function ocultarProveedorRapido() {
        document.getElementById('formProveedorRapido').classList.add('hidden');
    }
</script>

@endsection