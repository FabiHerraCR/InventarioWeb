<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventario Web</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            Inventario Web
        </h1>

        <p class="text-gray-600 mb-6">
            Inicie sesión para ingresar al sistema.
        </p>

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">
                    Correo electrónico
                </label>

                <input
                    type="email"
                    name="correo"
                    value="{{ old('correo') }}"
                    class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-gray-900"
                    placeholder="Ingrese su correo electrónicodale"
                    required
                >

                @error('correo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">
                    Contraseña
                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-gray-900"
                    placeholder="Ingrese su contraseña"
                    required
                >

                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full bg-gray-900 text-white p-3 rounded-lg font-semibold hover:bg-gray-700">
                Iniciar sesión
            </button>
        </form>

        <div class="mt-6 text-sm text-gray-500">
            <p><strong>Admin:</strong> admin@inventario.com / 123456</p>
            <p><strong>Vendedor:</strong> vendedor@inventario.com / 123456</p>
        </div>
    </div>

</body>
</html>