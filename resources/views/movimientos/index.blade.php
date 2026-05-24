@extends('layouts.app')

@section('titulo', 'Movimientos de Inventario')
@section('encabezado', 'Movimientos de Inventario')
@section('descripcion', 'Historial de entradas y salidas generadas por compras, ventas y anulaciones.')

@section('contenido')

    @forelse ($movimientosAgrupados as $origen => $detalles)

        @php
            $primerMovimiento = $detalles->first();

            $fechaOriginal = $primerMovimiento->fecha_movimiento
                ?? $primerMovimiento->fecha
                ?? null;

            $fechaMovimiento = $fechaOriginal
                ? \Carbon\Carbon::parse($fechaOriginal)->format('d/m/Y H:i')
                : '';
        @endphp

        <div class="bg-white shadow-md border border-gray-200 rounded-xl overflow-hidden mb-5">

            <div class="bg-gray-900 text-white p-4 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg">
                        {{ $origen }}
                    </h3>

                    <p class="text-sm">
                        Fecha: {{ $fechaMovimiento }}
                        |
                        Usuario: {{ $primerMovimiento->usuario ?? '' }}
                    </p>
                </div>

                <div class="text-right">
                    <span class="inline-block px-3 py-1 rounded-full bg-gray-100 text-gray-800 text-sm">
                        {{ $detalles->count() }} movimiento(s)
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">ID</th>
                            <th class="p-3 text-left">Producto</th>
                            <th class="p-3 text-center">Tipo</th>
                            <th class="p-3 text-center">Cantidad</th>
                            <th class="p-3 text-center">Stock anterior</th>
                            <th class="p-3 text-center">Stock nuevo</th>
                            <th class="p-3 text-left">Descripción</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($detalles as $movimiento)
                            
@php
    
    $tipo = strtoupper(trim($movimiento->tipo_movimiento ?? ''));
                        
@endphp

                            <tr class="border-b">
                                <td class="p-3">
                                    {{ $movimiento->id_movimiento ?? $movimiento->id ?? '' }}
                                </td>

                                <td class="p-3 font-semibold">
                                    {{ $movimiento->nombre_producto ?? $movimiento->producto ?? '' }}
                                </td>

                                
                                <td class="p-3 text-center">
@if ($tipo == 'ENTRADA')
    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">
        Entrada
    </span>
@elseif ($tipo == 'SALIDA')
    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm">
        Salida
    </span>
@else
    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm">
        {{ $movimiento->tipo_movimiento }}
    </span>
@endif
                                </td>

                                <td class="p-3 text-center">
                                    {{ $movimiento->cantidad }}
                                </td>

                                <td class="p-3 text-center">
                                    {{ $movimiento->stock_anterior }}
                                </td>

                                <td class="p-3 text-center font-semibold">
                                    {{ $movimiento->stock_nuevo }}
                                </td>

                                <td class="p-3">
                                    {{ $movimiento->descripcion }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    @empty

        <div class="bg-white p-4 rounded-xl shadow text-center text-gray-500">
            No hay movimientos registrados.
        </div>

    @endforelse

@endsection