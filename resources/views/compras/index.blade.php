@extends('layouts.app')

@section('titulo', 'Compras')
@section('encabezado', 'Compras')
@section('descripcion', 'Detalle de compras registradas en el sistema.')

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
        <a href="{{ route('compras.create') }}"
           class="inline-block bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700">
            Nueva compra
        </a>
    </div>

    <div class="bg-white shadow rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="p-3 text-left">Compra</th>
                        <th class="p-3 text-left">Fecha</th>
                        <th class="p-3 text-left">Proveedor</th>
                        <th class="p-3 text-left">Usuario</th>
                        <th class="p-3 text-left">Producto</th>
                        <th class="p-3 text-center">Cantidad</th>
                        <th class="p-3 text-right">Precio</th>
                        <th class="p-3 text-right">Subtotal</th>
                        <th class="p-3 text-right">Total</th>
                        <th class="p-3 text-center">Estado</th>
                        <th class="p-3 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($compras as $compra)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $compra->id_compra }}</td>
                            <td class="p-3">{{ $compra->fecha_compra }}</td>
                            <td class="p-3 font-semibold">{{ $compra->nombre_proveedor }}</td>
                            <td class="p-3">{{ $compra->usuario_registra }}</td>
                            <td class="p-3">{{ $compra->nombre_producto }}</td>
                            <td class="p-3 text-center">{{ $compra->cantidad }}</td>
                            <td class="p-3 text-right">₡{{ number_format($compra->precio_unitario, 2) }}</td>
                            <td class="p-3 text-right">₡{{ number_format($compra->subtotal, 2) }}</td>
                            <td class="p-3 text-right font-semibold">₡{{ number_format($compra->total, 2) }}</td>

                            <td class="p-3 text-center">
                                @if ($compra->estado == 'REGISTRADA')
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                                        Registrada
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm">
                                        Anulada
                                    </span>
                                @endif
                            </td>

                            <td class="p-3 text-center">
                                @if ($compra->estado == 'REGISTRADA')
                                    <form action="{{ route('compras.destroy', $compra->id_compra) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Seguro que desea anular esta compra?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                                            Anular
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-sm">Sin acciones</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection