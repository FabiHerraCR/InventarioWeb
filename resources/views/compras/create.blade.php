@extends('layouts.app')

@section('titulo', 'Nueva compra')
@section('encabezado', 'Nueva compra')
@section('descripcion', 'Formulario para registrar una compra y aumentar inventario automáticamente.')

@section('contenido')

    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-6 rounded-xl shadow max-w-7xl">
        <form action="{{ route('compras.store') }}" method="POST" novalidate>
            @csrf

            <div class="mb-4 max-w-2xl">
                <label class="block font-semibold mb-2">Proveedor</label>
                <select id="id_proveedor" name="id_proveedor" class="w-full border rounded-lg p-3" required>
                    <option value="">Seleccione un proveedor</option>

                    @foreach ($proveedores as $proveedor)
                        <option value="{{ $proveedor->id_proveedor }}"
                            {{ old('id_proveedor') == $proveedor->id_proveedor ? 'selected' : '' }}>
                            {{ $proveedor->nombre_proveedor }}
                        </option>
                    @endforeach
                </select>

                @error('id_proveedor')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

<h3 class="text-lg font-bold mb-3">Productos de la compra</h3>

<div id="detalleCompra">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 mb-3 fila-producto">

        <div class="lg:col-span-4">
            <label class="block font-semibold mb-2">Producto</label>
            <select name="productos[]"
                    class="producto-select w-full border rounded-lg p-3"
                    required>
                <option value="" data-precio="0" data-proveedor="">
                    Primero seleccione un proveedor
                </option>

                @foreach ($productos as $producto)
                    <option value="{{ $producto->id_producto }}"
                            data-precio="{{ $producto->precio_compra }}"
                            data-proveedor="{{ $producto->id_proveedor }}">
                        {{ $producto->nombre_producto }} - Stock: {{ $producto->stock }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="lg:col-span-1">
            <label class="block font-semibold mb-2">Cantidad</label>
            <input type="number"
                   name="cantidades[]"
                   class="cantidad-input w-full border rounded-lg p-3"
                   min="1"
                   value="1"
                   required>
        </div>

        <div class="lg:col-span-2">
            <label class="block font-semibold mb-2">Precio unitario</label>
            <input type="number"
                   name="precios[]"
                   class="precio-input w-full border rounded-lg p-3 bg-gray-100"
                   min="0"
                   step="0.01"
                   value="0"
                   readonly
                   required>
        </div>

        <div class="lg:col-span-2">
            <label class="block font-semibold mb-2">Total</label>
            <input type="text"
                   class="subtotal-input w-full border rounded-lg p-3 bg-gray-100"
                   value="₡0.00"
                   readonly>
        </div>

        <div class="lg:col-span-2 flex items-end">
            <button type="button"
                    onclick="eliminarFilaCompra(this)"
                    class="bg-red-600 text-white px-6 py-3 rounded hover:bg-red-700">
                Quitar
            </button>
        </div>

    </div>
</div>

<div class="mb-4">
    <button type="button"
            onclick="agregarFilaCompra()"
            class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800">
        + Agregar producto
    </button>
</div>

<div class="mb-6 bg-gray-100 p-4 rounded-lg">
    <p class="font-semibold text-gray-700">Total estimado de la compra</p>
    <p id="totalCompra" class="text-2xl font-bold text-gray-900">
        ₡0.00
    </p>
</div>

<div class="flex gap-2">
    <button type="submit"
            class="bg-gray-900 text-white px-4 py-2 rounded hover:bg-gray-700">
        Registrar compra
    </button>

    <a href="{{ route('compras.index') }}"
       class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
        Cancelar
    </a>
</div>
        </form>
    </div>

<script>
    const proveedorSelect = document.getElementById('id_proveedor');

    function formatoColones(valor) {
        return '₡' + valor.toLocaleString('es-CR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function calcularSubtotalFila(fila) {
        const cantidadInput = fila.querySelector('.cantidad-input');
        const precioInput = fila.querySelector('.precio-input');
        const subtotalInput = fila.querySelector('.subtotal-input');

        const cantidad = parseInt(cantidadInput.value) || 0;
        const precio = parseFloat(precioInput.value) || 0;

        const subtotal = cantidad * precio;

        subtotalInput.value = formatoColones(subtotal);

        return subtotal;
    }

    function calcularTotalCompra() {
        let total = 0;

        document.querySelectorAll('.fila-producto').forEach(function (fila) {
            total += calcularSubtotalFila(fila);
        });

        document.getElementById('totalCompra').textContent = formatoColones(total);
    }

    function actualizarPrecioDesdeProducto(select) {
        const fila = select.closest('.fila-producto');
        const precioInput = fila.querySelector('.precio-input');
        const opcion = select.options[select.selectedIndex];
        const precio = parseFloat(opcion.getAttribute('data-precio')) || 0;

        precioInput.value = precio;
        calcularTotalCompra();
    }

    function actualizarProductosDisponibles(limpiarSeleccion = false) {
        const idProveedor = proveedorSelect.value;

        const productosSeleccionados = Array.from(document.querySelectorAll('.producto-select'))
            .map(select => select.value)
            .filter(value => value !== '');

        document.querySelectorAll('.producto-select').forEach(function (select) {
            const valorActual = select.value;

            Array.from(select.options).forEach(function (option) {
                const proveedorProducto = option.getAttribute('data-proveedor');

                if (option.value === '') {
                    option.hidden = false;
                    option.disabled = false;

                    if (idProveedor === '') {
                        option.textContent = 'Primero seleccione un proveedor';
                    } else {
                        option.textContent = 'Seleccione un producto';
                    }

                    return;
                }

                const perteneceAlProveedor = idProveedor !== '' && proveedorProducto === idProveedor;
                const yaSeleccionadoEnOtraFila =
                    productosSeleccionados.includes(option.value) && option.value !== valorActual;

                if (perteneceAlProveedor && !yaSeleccionadoEnOtraFila) {
                    option.hidden = false;
                    option.disabled = false;
                } else {
                    option.hidden = true;
                    option.disabled = true;
                }
            });

            const opcionActual = select.options[select.selectedIndex];

            if (limpiarSeleccion || !opcionActual || opcionActual.disabled || opcionActual.hidden) {
                select.value = '';

                const fila = select.closest('.fila-producto');
                fila.querySelector('.precio-input').value = 0;
                fila.querySelector('.subtotal-input').value = '₡0.00';
            }
        });

        calcularTotalCompra();
    }

    function agregarFilaCompra() {
        const contenedor = document.getElementById('detalleCompra');
        const primeraFila = document.querySelector('.fila-producto');
        const nuevaFila = primeraFila.cloneNode(true);

        nuevaFila.querySelector('.producto-select').value = '';
        nuevaFila.querySelector('.cantidad-input').value = 1;
        nuevaFila.querySelector('.precio-input').value = 0;
        nuevaFila.querySelector('.subtotal-input').value = '₡0.00';

        contenedor.appendChild(nuevaFila);

        activarEventosCompra();
        actualizarProductosDisponibles(false);
        calcularTotalCompra();
    }

    function eliminarFilaCompra(boton) {
        const filas = document.querySelectorAll('.fila-producto');

        if (filas.length === 1) {
            alert('Debe quedar al menos un producto en la compra.');
            return;
        }

        boton.closest('.fila-producto').remove();

        actualizarProductosDisponibles(false);
        calcularTotalCompra();
    }

    function activarEventosCompra() {
        document.querySelectorAll('.producto-select').forEach(function (select) {
            select.onchange = function () {
                actualizarPrecioDesdeProducto(this);
                actualizarProductosDisponibles(false);
                calcularTotalCompra();
            };
        });

        document.querySelectorAll('.cantidad-input').forEach(function (input) {
            input.oninput = calcularTotalCompra;
        });

        document.querySelectorAll('.precio-input').forEach(function (input) {
            input.oninput = calcularTotalCompra;
        });
    }

    proveedorSelect.addEventListener('change', function () {
        actualizarProductosDisponibles(true);
    });

    activarEventosCompra();
    actualizarProductosDisponibles(false);
    calcularTotalCompra();
</script>

@endsection