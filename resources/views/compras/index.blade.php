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

    @forelse ($comprasAgrupadas as $idCompra => $detalles)

        @php
            $compra = $detalles->first();
            $fechaCompra = \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y H:i');
        @endphp

        <div class="bg-white shadow-md border border-gray-200 rounded-xl overflow-hidden mb-5">

            <div class="bg-gray-900 text-white p-4 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg">
                        Compra #{{ $compra->id_compra }}
                    </h3>

                    <p class="text-sm">
                        Fecha: {{ $fechaCompra }}
                        |
                        Proveedor: {{ $compra->nombre_proveedor }}
                        |
                        Usuario: {{ $compra->usuario_registra }}
                    </p>
                </div>

                <div class="text-right">
                    <p class="font-bold text-lg">
                        Total: ₡{{ number_format($compra->total, 2) }}
                    </p>

                    @if ($compra->estado == 'REGISTRADA')
                        <span class="inline-block mt-1 px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                            Registrada
                        </span>
                    @else
                        <span class="inline-block mt-1 px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm">
                            Anulada
                        </span>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Producto</th>
                            <th class="p-3 text-center">Cantidad</th>
                            <th class="p-3 text-right">Precio</th>
                            <th class="p-3 text-right">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($detalles as $detalle)
                            <tr class="border-b">
                                <td class="p-3 font-semibold">
                                    {{ $detalle->nombre_producto }}
                                </td>

                                <td class="p-3 text-center">
                                    {{ $detalle->cantidad }}
                                </td>

                                <td class="p-3 text-right">
                                    ₡{{ number_format($detalle->precio_unitario, 2) }}
                                </td>

                                <td class="p-3 text-right font-semibold">
                                    ₡{{ number_format($detalle->subtotal, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-gray-50 flex justify-end">
                @if ($compra->estado == 'REGISTRADA')
                    <form action="{{ route('compras.destroy', $compra->id_compra) }}"
                          method="POST"
                          onsubmit="return confirm('¿Seguro que desea anular esta compra?');">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            Anular compra
                        </button>
                    </form>
                @else
                    <span class="text-gray-400">
                        Compra sin acciones disponibles
                    </span>
                @endif
            </div>

        </div>

    @empty

        <div class="bg-white p-4 rounded-xl shadow text-center text-gray-500">
            No hay compras registradas.
        </div>

    @endforelse

@endsection