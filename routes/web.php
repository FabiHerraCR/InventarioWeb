<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\CategoriaController;

// RUTAS DE AUTENTICACION

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


// RUTAS PROTEGIDAS CON SESION

Route::middleware('sesion')->group(function () {

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Opcional: si alguien escribe /inicio, lo manda al panel
    Route::get('/inicio', function () {
        return redirect()->route('dashboard');
    })->name('inicio');


    // RUTAS PARA ADMINISTRADOR Y VENDEDOR

    Route::middleware('rol:ADMINISTRADOR,VENDEDOR')->group(function () {

        // Inventario solo consulta para ambos roles
        Route::get('/inventario', [InventarioController::class, 'index'])
            ->name('inventario.index');

        // Clientes solo listado para ambos roles
        Route::get('/clientes', [ClienteController::class, 'index'])
            ->name('clientes.index');

        // Ventas
        Route::get('/ventas', [VentaController::class, 'index'])
            ->name('ventas.index');

        Route::get('/ventas/crear', [VentaController::class, 'create'])
            ->name('ventas.create');

        Route::post('/ventas/guardar', [VentaController::class, 'store'])
            ->name('ventas.store');

        Route::delete('/ventas/{id}/anular', [VentaController::class, 'destroy'])
            ->name('ventas.destroy');

        // Movimientos
        Route::get('/movimientos', [MovimientoController::class, 'index'])
            ->name('movimientos.index');

        // Reportes
        Route::get('/reportes', [ReporteController::class, 'index'])
            ->name('reportes.index');
    });


    // RUTAS SOLO PARA ADMINISTRADOR

    Route::middleware('rol:ADMINISTRADOR')->group(function () {

        // CRUD DE CLIENTES - SOLO ADMINISTRADOR

        Route::get('/clientes/crear', [ClienteController::class, 'create'])
            ->name('clientes.create');

        Route::post('/clientes/guardar', [ClienteController::class, 'store'])
            ->name('clientes.store');

        Route::get('/clientes/{id}/editar', [ClienteController::class, 'edit'])
            ->name('clientes.edit');

        Route::put('/clientes/{id}/actualizar', [ClienteController::class, 'update'])
            ->name('clientes.update');

        Route::delete('/clientes/{id}/eliminar', [ClienteController::class, 'destroy'])
            ->name('clientes.destroy');


        // CRUD DE INVENTARIO - SOLO ADMINISTRADOR

        Route::get('/inventario/crear', [InventarioController::class, 'create'])
            ->name('inventario.create');

        Route::post('/inventario/guardar', [InventarioController::class, 'store'])
            ->name('inventario.store');

        Route::get('/inventario/{id}/editar', [InventarioController::class, 'edit'])
            ->name('inventario.edit');

        Route::put('/inventario/{id}/actualizar', [InventarioController::class, 'update'])
            ->name('inventario.update');

        Route::delete('/inventario/{id}/eliminar', [InventarioController::class, 'destroy'])
            ->name('inventario.destroy');


        // COMPRAS - SOLO ADMINISTRADOR

        Route::get('/compras', [CompraController::class, 'index'])
            ->name('compras.index');

        Route::get('/compras/crear', [CompraController::class, 'create'])
            ->name('compras.create');

        Route::post('/compras/guardar', [CompraController::class, 'store'])
            ->name('compras.store');

        Route::delete('/compras/{id}/anular', [CompraController::class, 'destroy'])
            ->name('compras.destroy');


        // PROVEEDORES - SOLO ADMINISTRADOR

        Route::get('/proveedores', [ProveedorController::class, 'index'])
            ->name('proveedores.index');

        Route::get('/proveedores/crear', [ProveedorController::class, 'create'])
            ->name('proveedores.create');

        Route::post('/proveedores/guardar', [ProveedorController::class, 'store'])
            ->name('proveedores.store');

        Route::get('/proveedores/{id}/editar', [ProveedorController::class, 'edit'])
            ->name('proveedores.edit');

        Route::put('/proveedores/{id}/actualizar', [ProveedorController::class, 'update'])
            ->name('proveedores.update');

        Route::delete('/proveedores/{id}/eliminar', [ProveedorController::class, 'destroy'])
            ->name('proveedores.destroy');


        // CATEGORIAS - SOLO ADMINISTRADOR

        Route::get('/categorias', [CategoriaController::class, 'index'])
            ->name('categorias.index');

        Route::get('/categorias/crear', [CategoriaController::class, 'create'])
            ->name('categorias.create');

        Route::post('/categorias/guardar', [CategoriaController::class, 'store'])
            ->name('categorias.store');

        Route::get('/categorias/{id}/editar', [CategoriaController::class, 'edit'])
            ->name('categorias.edit');

        Route::put('/categorias/{id}/actualizar', [CategoriaController::class, 'update'])
            ->name('categorias.update');

        Route::delete('/categorias/{id}/eliminar', [CategoriaController::class, 'destroy'])
            ->name('categorias.destroy');


        // RUTAS TEMPORALES DE PRUEBA ORACLE - SOLO ADMINISTRADOR

        Route::get('/probar-oracle', function () {
            $usuario = DB::select('SELECT USER FROM DUAL');
            $tablas = DB::select('SELECT TABLE_NAME FROM USER_TABLES ORDER BY TABLE_NAME');

            return [
                'usuario' => $usuario,
                'tablas' => $tablas,
            ];
        });

        Route::get('/productos-oracle', function () {
            $productos = DB::select('SELECT * FROM VW_INVENTARIO_GENERAL ORDER BY ID_PRODUCTO');

            return $productos;
        });
    });
});

// RUTAS TEMPORALES DE PRUEBA ORACLE

Route::get('/probar-oracle', function () {
    $usuario = DB::select('SELECT USER FROM DUAL');
    $tablas = DB::select('SELECT TABLE_NAME FROM USER_TABLES ORDER BY TABLE_NAME');

    return [
        'usuario' => $usuario,
        'tablas' => $tablas,
    ];
});

Route::get('/productos-oracle', function () {
    $productos = DB::select('SELECT * FROM VW_INVENTARIO_GENERAL ORDER BY ID_PRODUCTO');

    return $productos;
});