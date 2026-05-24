@extends('layouts.app')

@section('titulo', 'Categorías')
@section('encabezado', 'Categorías')
@section('descripcion', 'Listado de categorías registradas en el sistema.')

@section('contenido')

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

    <div class="mb-4">
        <a href="{{ route('categorias.create') }}"
           class="inline-block bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700">
            Nueva categoría
        </a>
    </div>

    <div class="bg-white shadow-md border border-gray-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Categoría</th>
                        <th class="p-3 text-left">Descripción</th>
                        <th class="p-3 text-center">Estado</th>
                        <th class="p-3 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($categorias as $categoria)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $categoria->id_categoria }}</td>

                            <td class="p-3 font-semibold">
                                {{ $categoria->nombre_categoria }}
                            </td>

                            <td class="p-3">
                                {{ $categoria->descripcion }}
                            </td>

                            <td class="p-3 text-center">
                                @if ($categoria->estado == 'A')
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                                        Activo
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm">
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            <td class="p-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('categorias.edit', $categoria->id_categoria) }}"
                                       class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                        Editar
                                    </a>

                                    @if ($categoria->estado == 'A')
                                        <form action="{{ route('categorias.destroy', $categoria->id_categoria) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Seguro que desea desactivar esta categoría?');">
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection