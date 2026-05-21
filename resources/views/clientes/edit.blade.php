@extends('layouts.app')

@section('titulo', 'Editar cliente')
@section('encabezado', 'Editar cliente')
@section('descripcion', 'Formulario para actualizar la información del cliente.')

@section('contenido')

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow max-w-3xl">
        <form action="{{ route('clientes.update', $cliente->id_cliente) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-semibold mb-2">Nombre del cliente</label>
                <input type="text" name="nombre_cliente"
                       value="{{ old('nombre_cliente', $cliente->nombre_cliente) }}"
                       class="w-full border rounded-lg p-3" required>
                @error('nombre_cliente')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">Cédula</label>
                <input type="text" name="cedula"
                       value="{{ old('cedula', $cliente->cedula) }}"
                       class="w-full border rounded-lg p-3" required>
                @error('cedula')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">Teléfono</label>
                <input type="text" name="telefono"
                       value="{{ old('telefono', $cliente->telefono) }}"
                       class="w-full border rounded-lg p-3">
                        @error('telefono')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">Correo</label>
                <input type="email" name="correo"
                       value="{{ old('correo', $cliente->correo) }}"
                       class="w-full border rounded-lg p-3">
                @error('correo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">Dirección</label>
                <textarea name="direccion"
                          class="w-full border rounded-lg p-3">{{ old('direccion', $cliente->direccion) }}</textarea>
            </div>

            <div class="mb-6">
                <label class="block font-semibold mb-2">Estado</label>
                <select name="estado" class="w-full border rounded-lg p-3">
                    <option value="A" {{ old('estado', $cliente->estado) == 'A' ? 'selected' : '' }}>
                        Activo
                    </option>
                    <option value="I" {{ old('estado', $cliente->estado) == 'I' ? 'selected' : '' }}>
                        Inactivo
                    </option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Actualizar
                </button>

                <a href="{{ route('clientes.index') }}"
                   class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

@endsection