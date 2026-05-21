@extends('layouts.app')

@section('titulo', 'Proveedores')
@section('encabezado', 'Proveedores')
@section('descripcion', 'Listado de proveedores registrados en el sistema.')

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
        <a href="{{ route('proveedores.create') }}"
           class="inline-block bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700">
            Nuevo proveedor
        </a>
    </div>

    <div class="bg-white shadow rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Proveedor</th>
                        <th class="p-3 text-left">Teléfono</th>
                        <th class="p-3 text-left">Correo</th>
                        <th class="p-3 text-left">Dirección</th>
                        <th class="p-3 text-center">Estado</th>
                        <th class="p-3 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($proveedores as $proveedor)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $proveedor->id_proveedor }}</td>
                            <td class="p-3 font-semibold">{{ $proveedor->nombre_proveedor }}</td>
                            <td class="p-3">{{ $proveedor->telefono }}</td>
                            <td class="p-3">{{ $proveedor->correo }}</td>
                            <td class="p-3">{{ $proveedor->direccion }}</td>

                            <td class="p-3 text-center">
                                @if ($proveedor->estado == 'A')
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
                                    <a href="{{ route('proveedores.edit', $proveedor->id_proveedor) }}"
                                       class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                        Editar
                                    </a>

                                    @if ($proveedor->estado == 'A')
                                        <form action="{{ route('proveedores.destroy', $proveedor->id_proveedor) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Seguro que desea desactivar este proveedor?');">
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