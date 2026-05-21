@extends('layouts.app')

@section('titulo', 'Movimientos de Inventario')
@section('encabezado', 'Movimientos de Inventario')
@section('descripcion', 'Historial de entradas y salidas generadas por compras, ventas y anulaciones.')

@section('contenido')

    <div class="bg-white shadow rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Fecha</th>
                        <th class="p-3 text-left">Producto</th>
                        <th class="p-3 text-left">Usuario</th>
                        <th class="p-3 text-center">Tipo</th>
                        <th class="p-3 text-center">Cantidad</th>
                        <th class="p-3 text-center">Stock anterior</th>
                        <th class="p-3 text-center">Stock nuevo</th>
                        <th class="p-3 text-left">Descripción</th>
                        <th class="p-3 text-center">Origen</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($movimientos as $movimiento)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $movimiento->id_movimiento }}</td>
                            <td class="p-3">{{ $movimiento->fecha_movimiento }}</td>
                            <td class="p-3 font-semibold">{{ $movimiento->nombre_producto }}</td>
                            <td class="p-3">{{ $movimiento->usuario }}</td>

                            <td class="p-3 text-center">
                                @if ($movimiento->tipo_movimiento == 'ENTRADA')
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
                                        Entrada
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm">
                                        Salida
                                    </span>
                                @endif
                            </td>

                            <td class="p-3 text-center">{{ $movimiento->cantidad }}</td>
                            <td class="p-3 text-center">{{ $movimiento->stock_anterior }}</td>
                            <td class="p-3 text-center font-semibold">{{ $movimiento->stock_nuevo }}</td>
                            <td class="p-3">{{ $movimiento->descripcion }}</td>

                            <td class="p-3 text-center">
                                @if ($movimiento->id_venta)
                                    Venta #{{ $movimiento->id_venta }}
                                @elseif ($movimiento->id_compra)
                                    Compra #{{ $movimiento->id_compra }}
                                @else
                                    Manual
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-4 text-center text-gray-500">
                                No hay movimientos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection