@extends('layouts.app')

@section('titulo', 'Nueva venta')
@section('encabezado', 'Nueva venta')
@section('descripcion', 'Formulario para registrar una venta y descontar inventario automáticamente.')

@section('contenido')

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow max-w-5xl">
        <form action="{{ route('ventas.store') }}" method="POST" novalidate>
            @csrf

            <div class="mb-4">
                <label class="block font-semibold mb-2">Cliente</label>
                <select name="id_cliente" class="w-full border rounded-lg p-3" required>
                    <option value="">Seleccione un cliente</option>

                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id_cliente }}"
                            {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}>
                            {{ $cliente->nombre_cliente }}
                        </option>
                    @endforeach
                </select>

                @error('id_cliente')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <h3 class="text-lg font-bold mb-3">Productos de la venta</h3>

            <div id="detalleVenta">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 mb-3 fila-producto">
                    <div class="md:col-span-7">
                        <label class="block font-semibold mb-2">Producto</label>
                        <select name="productos[]"
                                class="producto-select w-full border rounded-lg p-3"
                                required>
                            <option value="" data-precio="0" data-stock="0">
                                Seleccione un producto
                            </option>

                            @foreach ($productos as $producto)
                                <option value="{{ $producto->id_producto }}"
                                        data-precio="{{ $producto->precio_venta }}"
                                        data-stock="{{ $producto->stock }}">
                                    {{ $producto->nombre_producto }}
                                    - Precio: ₡{{ number_format($producto->precio_venta, 2) }}
                                    - Stock: {{ $producto->stock }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-3">
                        <label class="block font-semibold mb-2">Cantidad</label>
                        <input type="number"
                               name="cantidades[]"
                               class="cantidad-input w-full border rounded-lg p-3"
                               min="1"
                               value="1"
                               required>
                    </div>

                    <div class="md:col-span-2 flex items-end">
                        <button type="button"
                                onclick="eliminarFila(this)"
                                class="bg-red-600 text-white px-3 py-3 rounded hover:bg-red-700 w-full">
                            Quitar
                        </button>
                    </div>
                </div>
            </div>

            @error('productos')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror

            @error('cantidades')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror

            <div class="mb-4">
                <button type="button"
                        onclick="agregarFila()"
                        class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">
                    + Agregar producto
                </button>
            </div>

            <div class="mb-6 bg-gray-100 p-4 rounded-lg">
                <p class="font-semibold text-gray-700">Total estimado de la venta</p>
                <p id="totalVenta" class="text-2xl font-bold text-gray-900">
                    ₡0.00
                </p>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Registrar venta
                </button>

                <a href="{{ route('ventas.index') }}"
                   class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <script>
    function formatoColones(valor) {
        return '₡' + valor.toLocaleString('es-CR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function calcularTotalVenta() {
        let total = 0;

        document.querySelectorAll('.fila-producto').forEach(function (fila) {
            const productoSelect = fila.querySelector('.producto-select');
            const cantidadInput = fila.querySelector('.cantidad-input');

            const opcion = productoSelect.options[productoSelect.selectedIndex];
            const precio = parseFloat(opcion.getAttribute('data-precio')) || 0;
            const cantidad = parseInt(cantidadInput.value) || 0;

            total += precio * cantidad;
        });

        document.getElementById('totalVenta').textContent = formatoColones(total);
    }

    function actualizarProductosSeleccionados() {
        const productosSeleccionados = Array.from(document.querySelectorAll('.producto-select'))
            .map(select => select.value)
            .filter(value => value !== '');

        document.querySelectorAll('.producto-select').forEach(function (select) {
            const valorActual = select.value;

            Array.from(select.options).forEach(function (option) {
                if (option.value === '') {
                    option.disabled = false;
                    return;
                }

                if (productosSeleccionados.includes(option.value) && option.value !== valorActual) {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            });
        });
    }

    function agregarFila() {
        const contenedor = document.getElementById('detalleVenta');
        const primeraFila = document.querySelector('.fila-producto');
        const nuevaFila = primeraFila.cloneNode(true);

        nuevaFila.querySelector('.producto-select').value = '';
        nuevaFila.querySelector('.cantidad-input').value = 1;

        contenedor.appendChild(nuevaFila);

        activarEventos();
        actualizarProductosSeleccionados();
        calcularTotalVenta();
    }

    function eliminarFila(boton) {
        const filas = document.querySelectorAll('.fila-producto');

        if (filas.length === 1) {
            alert('Debe quedar al menos un producto en la venta.');
            return;
        }

        boton.closest('.fila-producto').remove();

        actualizarProductosSeleccionados();
        calcularTotalVenta();
    }

    function activarEventos() {
        document.querySelectorAll('.producto-select').forEach(function (select) {
            select.onchange = function () {
                calcularTotalVenta();
                actualizarProductosSeleccionados();
            };
        });

        document.querySelectorAll('.cantidad-input').forEach(function (input) {
            input.oninput = calcularTotalVenta;
        });
    }

    activarEventos();
    actualizarProductosSeleccionados();
    calcularTotalVenta();
</script>

@endsection