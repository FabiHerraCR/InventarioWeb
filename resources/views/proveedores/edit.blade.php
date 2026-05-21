@extends('layouts.app')

@section('titulo', 'Editar proveedor')
@section('encabezado', 'Editar proveedor')
@section('descripcion', 'Formulario para actualizar la información del proveedor.')

@section('contenido')

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow max-w-3xl">
        <form action="{{ route('proveedores.update', $proveedor->id_proveedor) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-semibold mb-2">Nombre del proveedor</label>
                <input type="text"
                       name="nombre_proveedor"
                       value="{{ old('nombre_proveedor', $proveedor->nombre_proveedor) }}"
                       class="w-full border rounded-lg p-3"
                       required>

                @error('nombre_proveedor')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">Teléfono</label>
                <input type="text"
                       name="telefono"
                       value="{{ old('telefono', $proveedor->telefono) }}"
                       class="w-full border rounded-lg p-3">

                @error('telefono')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

            <div class="mb-4">
                <label class="block font-semibold mb-2">Correo</label>
                <input type="email"
                       name="correo"
                       value="{{ old('correo', $proveedor->correo) }}"
                       class="w-full border rounded-lg p-3"
                       placeholder="correo@ejemplo.com">

                @error('correo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">Dirección</label>
                <textarea name="direccion"
                          class="w-full border rounded-lg p-3">{{ old('direccion', $proveedor->direccion) }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block font-semibold mb-2">Estado</label>
                <select name="estado" class="w-full border rounded-lg p-3">
                    <option value="A" {{ old('estado', $proveedor->estado) == 'A' ? 'selected' : '' }}>
                        Activo
                    </option>

                    <option value="I" {{ old('estado', $proveedor->estado) == 'I' ? 'selected' : '' }}>
                        Inactivo
                    </option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Actualizar
                </button>

                <a href="{{ route('proveedores.index') }}"
                   class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

@endsection