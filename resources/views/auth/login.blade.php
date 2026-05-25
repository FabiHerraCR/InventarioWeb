<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventario Web</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[linear-gradient(135deg,#111827_0%,#12352f_42%,#223047_72%,#443725_100%)] text-gray-900">

    <main class="flex min-h-screen items-center justify-center px-4 py-8 sm:px-6">
        <section class="w-full max-w-md border border-white/50 bg-white/95 p-6 shadow-2xl shadow-black/35 backdrop-blur sm:p-8">
            <div class="mb-8 text-center">
                <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center bg-gray-950 text-xl font-bold text-white shadow-lg shadow-emerald-950/35">
                    IW
                </div>

                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-emerald-700">
                    Bienvenido
                </p>

                <h1 class="mt-3 text-3xl font-bold text-gray-950">
                    Iniciar sesión
                </h1>

                <p class="mt-3 text-sm leading-6 text-gray-600">
                    Accede al sistema de inventario con tus credenciales.
                </p>
            </div>

            @if (session('error'))
                <div class="mb-5 border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="correo" class="block text-sm font-semibold text-gray-800">
                        Correo electrónico
                    </label>

                    <input
                        id="correo"
                        type="email"
                        name="correo"
                        value="{{ old('correo') }}"
                        class="mt-2 w-full border border-gray-300 bg-white px-4 py-3 text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 @error('correo') border-red-300 focus:border-red-500 focus:ring-red-100 @enderror"
                        placeholder="Ingresa tu correo electrónico"
                        autocomplete="email"
                        required
                    >

                    @error('correo')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-800">
                        Contraseña
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="mt-2 w-full border border-gray-300 bg-white px-4 py-3 text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 @error('password') border-red-300 focus:border-red-500 focus:ring-red-100 @enderror"
                        placeholder="Ingresa tu contraseña"
                        autocomplete="current-password"
                        required
                    >

                    @error('password')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full bg-gray-950 px-4 py-3 font-semibold text-white transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-200">
                    Iniciar sesión
                </button>
            </form>

            <div class="mt-7 border border-gray-200 bg-gray-50 p-4">
                <p class="text-sm font-semibold text-gray-900">
                    Cuentas de prueba
                </p>

                <div class="mt-3 grid gap-2 text-sm text-gray-600">
                    <p><span class="font-semibold text-gray-800">Admin:</span> admin@inventario.com / 123456</p>
                    <p><span class="font-semibold text-gray-800">Vendedor:</span> vendedor@inventario.com / 123456</p>
                </div>
            </div>
        </section>
    </main>

</body>
</html>
