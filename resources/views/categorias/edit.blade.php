@extends('layouts.app')

@section('titulo', 'Editar categoría')
@section('encabezado', 'Editar categoría')
@section('descripcion', 'Formulario para actualizar la información de la categoría.')

@section('contenido')

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow max-w-3xl">
        <form action="{{ route('categorias.update', $categoria->id_categoria) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-semibold mb-2">Nombre de la categoría</label>
                <input type="text"
                       name="nombre_categoria"
                       value="{{ old('nombre_categoria', $categoria->nombre_categoria) }}"
                       class="w-full border rounded-lg p-3"
                       required>

                @error('nombre_categoria')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">Descripción</label>
                <textarea name="descripcion"
                          class="w-full border rounded-lg p-3">{{ old('descripcion', $categoria->descripcion) }}</textarea>

                @error('descripcion')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block font-semibold mb-2">Estado</label>
                <select name="estado" class="w-full border rounded-lg p-3">
                    <option value="A" {{ old('estado', $categoria->estado) == 'A' ? 'selected' : '' }}>
                        Activo
                    </option>

                    <option value="I" {{ old('estado', $categoria->estado) == 'I' ? 'selected' : '' }}>
                        Inactivo
                    </option>
                </select>

                @error('estado')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Actualizar
                </button>

                <a href="{{ route('categorias.index') }}"
                   class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

@endsection