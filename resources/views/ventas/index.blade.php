@extends('layouts.app')

@section('titulo', 'Ventas')
@section('encabezado', 'Ventas')
@section('descripcion', 'Detalle de ventas registradas en el sistema.')

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
    <a href="{{ route('ventas.create') }}"
       class="inline-block bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700">
        Nueva venta
    </a>
</div>

    <div class="bg-white shadow rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="p-3 text-left">Venta</th>
                        <th class="p-3 text-left">Fecha</th>
                        <th class="p-3 text-left">Cliente</th>
                        <th class="p-3 text-left">Vendedor</th>
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
                    @foreach ($ventas as $venta)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $venta->id_venta }}</td>
                            <td class="p-3">{{ $venta->fecha_venta }}</td>
                            <td class="p-3 font-semibold">{{ $venta->nombre_cliente }}</td>
                            <td class="p-3">{{ $venta->vendedor }}</td>
                            <td class="p-3">{{ $venta->nombre_producto }}</td>
                            <td class="p-3 text-center">{{ $venta->cantidad }}</td>
                            <td class="p-3 text-right">₡{{ number_format($venta->precio_unitario, 2) }}</td>
                            <td class="p-3 text-right">₡{{ number_format($venta->subtotal, 2) }}</td>
                            <td class="p-3 text-right font-semibold">₡{{ number_format($venta->total, 2) }}</td>
                            <td class="p-3 text-center">
                                @if ($venta->estado == 'REGISTRADA')
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
    @if ($venta->estado == 'REGISTRADA')
        <form action="{{ route('ventas.destroy', $venta->id_venta) }}"
              method="POST"
              onsubmit="return confirm('¿Seguro que desea anular esta venta?');">
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