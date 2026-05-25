@extends('layouts.app')

@section('titulo', 'Reportes')
@section('encabezado', 'Reportes')
@section('descripcion', $tituloReporte)
@section('contenido')

    <!-- TARJETAS RESUMEN -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h3 class="text-gray-500 text-sm">Productos activos</h3>
            <p class="text-3xl font-bold mt-2">{{ $resumen['total_productos'] }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h3 class="text-gray-500 text-sm">Clientes activos</h3>
            <p class="text-3xl font-bold mt-2">{{ $resumen['total_clientes'] }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h3 class="text-gray-500 text-sm">Ventas registradas</h3>
            <p class="text-3xl font-bold mt-2">{{ $resumen['total_ventas'] }}</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h3 class="text-gray-500 text-sm">Total vendido</h3>
            <p class="text-3xl font-bold mt-2">
                ₡{{ number_format($resumen['total_vendido'], 2) }}
            </p>
        </div>

    </div>

    <!-- GRAFICOS PRINCIPALES -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-3">
            <h3 class="text-xl font-bold mb-2">Productos más vendidos</h3>
            <p class="text-gray-500 text-sm mb-4">
                Cantidad de unidades vendidas por producto.
            </p>
            <div class="h-72">
    <canvas id="graficoProductos"></canvas>
</div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-3">
            <h3 class="text-xl font-bold mb-2">Ventas por día</h3>
            <p class="text-gray-500 text-sm mb-4">
                Total vendido agrupado por fecha.
            </p>
            <div class="h-72">
    <canvas id="graficoVentas"></canvas>
</div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
            <h3 class="text-xl font-bold mb-2">Stock actual por producto</h3>
            <p class="text-gray-500 text-sm mb-4">
                Distribución del inventario disponible.
            </p>
            <div class="h-72">
    <canvas id="graficoStock"></canvas>
</div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
            <h3 class="text-xl font-bold mb-4">Productos con stock bajo</h3>

            @if (count($stockBajo) > 0)
                <div class="max-h-72 overflow-y-auto">
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th class="p-3 text-left">Producto</th>
                                <th class="p-3 text-left">Categoría</th>
                                <th class="p-3 text-center">Stock</th>
                                <th class="p-3 text-center">Mínimo</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($stockBajo as $producto)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 font-semibold">{{ $producto->nombre_producto }}</td>
                                    <td class="p-3">{{ $producto->nombre_categoria }}</td>
                                    <td class="p-3 text-center">{{ $producto->stock }}</td>
                                    <td class="p-3 text-center">{{ $producto->stock_minimo }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">
                    No hay productos con stock bajo actualmente.
                </p>
            @endif
        </div>

    </div>

    <!-- TABLA DE VENTAS POR DIA -->
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-4">
        <h3 class="text-xl font-bold mb-4">Detalle de ventas por día</h3>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="p-3 text-left">Fecha</th>
                        <th class="p-3 text-center">Cantidad de ventas</th>
                        <th class="p-3 text-right">Total vendido</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach (collect($ventasPorDia)->sortByDesc('fecha') as $venta)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $venta->fecha }}</td>
                            <td class="p-3 text-center">{{ $venta->cantidad_ventas }}</td>
                            <td class="p-3 text-right">
                                ₡{{ number_format($venta->total_vendido, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const productosLabels = @json($productosLabels);
        const productosUnidades = @json($productosUnidades);

        const ventasFechas = @json($ventasFechas);
        const ventasTotales = @json($ventasTotales);

        const stockLabels = @json($stockLabels);
        const stockValores = @json($stockValores);

        new Chart(document.getElementById('graficoProductos'), {
            type: 'bar',
            data: {
                labels: productosLabels,
                datasets: [{
                    label: 'Unidades vendidas',
                    data: productosUnidades,
                    backgroundColor: '#111827'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('graficoVentas'), {
            type: 'line',
            data: {
                labels: ventasFechas,
                datasets: [{
                    label: 'Total vendido',
                    data: ventasTotales,
                    borderColor: '#111827',
                    backgroundColor: '#111827',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        new Chart(document.getElementById('graficoStock'), {
            type: 'doughnut',
            data: {
                labels: stockLabels,
                datasets: [{
                    label: 'Stock disponible',
                    data: stockValores,
                    backgroundColor: [
                        '#111827',
                        '#2563eb',
                        '#16a34a',
                        '#f97316',
                        '#dc2626',
                        '#9333ea'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endpush