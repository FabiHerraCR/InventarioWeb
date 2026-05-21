@extends('layouts.app')

@section('titulo', 'Clientes')
@section('encabezado', 'Clientes')
@section('descripcion', 'Listado de clientes registrados en el sistema.')

@section('contenido')

    @php
        $rol = session('usuario.rol');
    @endphp

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($rol === 'ADMINISTRADOR')
        <div class="mb-4">
            <a href="{{ route('clientes.create') }}"
               class="inline-block bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700">
                Nuevo cliente
            </a>
        </div>
    @endif

    <div class="bg-white shadow rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Cliente</th>
                        <th class="p-3 text-left">Cédula</th>
                        <th class="p-3 text-left">Teléfono</th>
                        <th class="p-3 text-left">Correo</th>
                        <th class="p-3 text-left">Dirección</th>
                        <th class="p-3 text-center">Estado</th>

                        @if ($rol === 'ADMINISTRADOR')
                            <th class="p-3 text-center">Acciones</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @foreach ($clientes as $cliente)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $cliente->id_cliente }}</td>
                            <td class="p-3 font-semibold">{{ $cliente->nombre_cliente }}</td>
                            <td class="p-3">{{ $cliente->cedula }}</td>
                            <td class="p-3">{{ $cliente->telefono }}</td>
                            <td class="p-3">{{ $cliente->correo }}</td>
                            <td class="p-3">{{ $cliente->direccion }}</td>
                            <td class="p-3 text-center">
                                @if ($cliente->estado == 'A')
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
                                        <a href="{{ route('clientes.edit', $cliente->id_cliente) }}"
                                           class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                            Editar
                                        </a>

                                        @if ($cliente->estado == 'A')
                                            <form action="{{ route('clientes.destroy', $cliente->id_cliente) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('¿Seguro que desea desactivar este cliente?');">
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