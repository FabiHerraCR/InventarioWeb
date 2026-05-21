<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        $rol = session('usuario.rol');
        $idUsuario = session('usuario.id_usuario');

        if ($rol === 'ADMINISTRADOR') {
            $condicionVentas = "";
            $parametros = [];
            $tituloReporte = "Reportes generales del sistema";
        } else {
            $condicionVentas = " AND ID_USUARIO = ? ";
            $parametros = [$idUsuario];
            $tituloReporte = "Reportes de mis ventas";
        }

        $totalVentas = DB::selectOne("
            SELECT COUNT(*) AS TOTAL
            FROM VENTAS
            WHERE ESTADO = 'REGISTRADA'
            $condicionVentas
        ", $parametros)->total;

        $totalVendido = DB::selectOne("
            SELECT NVL(SUM(TOTAL), 0) AS TOTAL
            FROM VENTAS
            WHERE ESTADO = 'REGISTRADA'
            $condicionVentas
        ", $parametros)->total;

        $resumen = [
            'total_productos' => DB::selectOne("
                SELECT COUNT(*) AS TOTAL
                FROM PRODUCTOS
                WHERE ESTADO = 'A'
            ")->total,

            'total_clientes' => DB::selectOne("
                SELECT COUNT(*) AS TOTAL
                FROM CLIENTES
                WHERE ESTADO = 'A'
            ")->total,

            'total_ventas' => $totalVentas,
            'total_vendido' => $totalVendido,
        ];

        if ($rol === 'ADMINISTRADOR') {
            $productosMasVendidos = DB::select("
                SELECT
                    NOMBRE_PRODUCTO,
                    TOTAL_UNIDADES_VENDIDAS,
                    TOTAL_VENDIDO
                FROM VW_PRODUCTOS_MAS_VENDIDOS
                ORDER BY TOTAL_UNIDADES_VENDIDAS DESC
            ");

            $ventasPorDia = DB::select("
                SELECT
                    TO_CHAR(FECHA, 'DD/MM/YYYY') AS FECHA,
                    CANTIDAD_VENTAS,
                    TOTAL_VENDIDO
                FROM VW_VENTAS_POR_DIA
                ORDER BY FECHA
            ");
        } else {
            $productosMasVendidos = DB::select("
                SELECT
                    P.NOMBRE_PRODUCTO,
                    SUM(DV.CANTIDAD) AS TOTAL_UNIDADES_VENDIDAS,
                    SUM(DV.SUBTOTAL) AS TOTAL_VENDIDO
                FROM VENTAS V
                INNER JOIN DETALLE_VENTAS DV ON V.ID_VENTA = DV.ID_VENTA
                INNER JOIN PRODUCTOS P ON DV.ID_PRODUCTO = P.ID_PRODUCTO
                WHERE V.ESTADO = 'REGISTRADA'
                AND V.ID_USUARIO = ?
                GROUP BY P.NOMBRE_PRODUCTO
                ORDER BY TOTAL_UNIDADES_VENDIDAS DESC
            ", [$idUsuario]);

            $ventasPorDia = DB::select("
                SELECT
                    TO_CHAR(TRUNC(FECHA_VENTA), 'DD/MM/YYYY') AS FECHA,
                    COUNT(ID_VENTA) AS CANTIDAD_VENTAS,
                    SUM(TOTAL) AS TOTAL_VENDIDO
                FROM VENTAS
                WHERE ESTADO = 'REGISTRADA'
                AND ID_USUARIO = ?
                GROUP BY TRUNC(FECHA_VENTA)
                ORDER BY TRUNC(FECHA_VENTA)
            ", [$idUsuario]);
        }

        $stockProductos = DB::select("
            SELECT
                NOMBRE_PRODUCTO,
                STOCK
            FROM VW_INVENTARIO_GENERAL
            WHERE ESTADO = 'A'
            ORDER BY ID_PRODUCTO
        ");

        $stockBajo = DB::select("
            SELECT *
            FROM VW_PRODUCTOS_STOCK_BAJO
            ORDER BY STOCK ASC
        ");

        $productosLabels = collect($productosMasVendidos)
            ->pluck('nombre_producto')
            ->toArray();

        $productosUnidades = collect($productosMasVendidos)
            ->pluck('total_unidades_vendidas')
            ->toArray();

        $ventasFechas = collect($ventasPorDia)
            ->pluck('fecha')
            ->toArray();

        $ventasTotales = collect($ventasPorDia)
            ->pluck('total_vendido')
            ->toArray();

        $stockLabels = collect($stockProductos)
            ->pluck('nombre_producto')
            ->toArray();

        $stockValores = collect($stockProductos)
            ->pluck('stock')
            ->toArray();

        return view('reportes.index', compact(
            'resumen',
            'productosMasVendidos',
            'ventasPorDia',
            'stockBajo',
            'productosLabels',
            'productosUnidades',
            'ventasFechas',
            'ventasTotales',
            'stockLabels',
            'stockValores',
            'tituloReporte'
        ));
    }
}