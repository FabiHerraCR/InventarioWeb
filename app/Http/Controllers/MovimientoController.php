<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class MovimientoController extends Controller
{
    public function index()
    {
        $rol = session('usuario.rol');
        $idUsuario = session('usuario.id_usuario');

        if ($rol === 'ADMINISTRADOR') {
            $movimientos = DB::select("
                SELECT
                    ID_MOVIMIENTO,
                    NOMBRE_PRODUCTO,
                    USUARIO,
                    TIPO_MOVIMIENTO,
                    CANTIDAD,
                    STOCK_ANTERIOR,
                    STOCK_NUEVO,
                    FECHA_MOVIMIENTO,
                    DESCRIPCION,
                    ID_COMPRA,
                    ID_VENTA
                FROM VW_MOVIMIENTOS_INVENTARIO
                ORDER BY ID_MOVIMIENTO DESC
            ");
        } else {
            $movimientos = DB::select("
                SELECT
                    ID_MOVIMIENTO,
                    NOMBRE_PRODUCTO,
                    USUARIO,
                    TIPO_MOVIMIENTO,
                    CANTIDAD,
                    STOCK_ANTERIOR,
                    STOCK_NUEVO,
                    FECHA_MOVIMIENTO,
                    DESCRIPCION,
                    ID_COMPRA,
                    ID_VENTA
                FROM VW_MOVIMIENTOS_INVENTARIO
                WHERE ID_USUARIO = ?
                ORDER BY ID_MOVIMIENTO DESC
            ", [$idUsuario]);
        }

        return view('movimientos.index', compact('movimientos'));
    }
}