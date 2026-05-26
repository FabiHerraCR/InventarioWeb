<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index()
{
    $rol = session('usuario.rol');
    $idUsuario = session('usuario.id_usuario');

    if ($rol === 'ADMINISTRADOR') {
        $ventas = DB::select("
            SELECT
                ID_VENTA,
                FECHA_VENTA,
                NOMBRE_CLIENTE,
                VENDEDOR,
                NOMBRE_PRODUCTO,
                CANTIDAD,
                PRECIO_UNITARIO,
                SUBTOTAL,
                TOTAL,
                ESTADO
            FROM VW_VENTAS_DETALLE
            ORDER BY ID_VENTA DESC
        ");
    } else {
        $ventas = DB::select("
            SELECT
                ID_VENTA,
                FECHA_VENTA,
                NOMBRE_CLIENTE,
                VENDEDOR,
                NOMBRE_PRODUCTO,
                CANTIDAD,
                PRECIO_UNITARIO,
                SUBTOTAL,
                TOTAL,
                ESTADO
            FROM VW_VENTAS_DETALLE
            WHERE ID_USUARIO = ?
            ORDER BY ID_VENTA DESC
        ", [$idUsuario]);
    }

    $ventasAgrupadas = collect($ventas)->groupBy('id_venta');

    return view('ventas.index', compact('ventasAgrupadas'));
}

public function create()
{
    $clientes = DB::select("
        SELECT ID_CLIENTE, NOMBRE_CLIENTE
        FROM CLIENTES
        WHERE ESTADO = 'A'
        ORDER BY NOMBRE_CLIENTE
    ");

    $categorias = DB::select("
        SELECT ID_CATEGORIA, NOMBRE_CATEGORIA
        FROM CATEGORIAS
        WHERE ESTADO = 'A'
        ORDER BY NOMBRE_CATEGORIA
    ");

    $productos = DB::select("
        SELECT
            ID_PRODUCTO,
            ID_CATEGORIA,
            NOMBRE_PRODUCTO,
            PRECIO_VENTA,
            STOCK
        FROM PRODUCTOS
        WHERE ESTADO = 'A'
        AND STOCK > 0
        ORDER BY NOMBRE_PRODUCTO
    ");

    return view('ventas.create', compact('clientes', 'categorias', 'productos'));
}

    public function store(Request $request)
{
    $request->validate([
        'id_cliente' => 'required',
        'productos' => 'required|array|min:1',
        'productos.*' => 'required',
        'cantidades' => 'required|array|min:1',
        'cantidades.*' => 'required|integer|min:1',
    ], [
        'id_cliente.required' => 'Debe seleccionar un cliente.',
        'productos.required' => 'Debe agregar al menos un producto.',
        'productos.*.required' => 'Debe seleccionar un producto.',
        'cantidades.*.required' => 'Debe ingresar la cantidad.',
        'cantidades.*.integer' => 'La cantidad debe ser un número entero.',
        'cantidades.*.min' => 'La cantidad debe ser mayor a cero.',
    ]);

    $idUsuario = session('usuario.id_usuario');

    try {
        DB::transaction(function () use ($request, $idUsuario) {
            DB::statement("
                DECLARE
                    V_ID_VENTA NUMBER;
                BEGIN
                    SP_INS_VENTA(?, ?, V_ID_VENTA);

                    FOR i IN 1 .. ? LOOP
                        NULL;
                    END LOOP;
                END;
            ", [
                $request->id_cliente,
                $idUsuario,
                count($request->productos),
            ]);

            $ultimaVenta = DB::selectOne("
                SELECT MAX(ID_VENTA) AS ID_VENTA
                FROM VENTAS
                WHERE ID_CLIENTE = ?
                AND ID_USUARIO = ?
            ", [
                $request->id_cliente,
                $idUsuario,
            ]);

            foreach ($request->productos as $index => $idProducto) {
                $cantidad = $request->cantidades[$index];

                DB::statement("
                    BEGIN
                        SP_INS_DETALLE_VENTA(?, ?, ?);
                    END;
                ", [
                    $ultimaVenta->id_venta,
                    $idProducto,
                    $cantidad,
                ]);
            }
        });

        return redirect()
            ->route('ventas.index')
            ->with('success', 'Venta registrada correctamente.');

    } catch (\Exception $e) {
        return back()
            ->with('error', 'No se pudo registrar la venta. Revise el stock disponible.')
            ->withInput();
    }
}

    public function destroy($id)
{
    $rol = session('usuario.rol');
    $idUsuario = session('usuario.id_usuario');

    try {
        $venta = DB::selectOne("
            SELECT ID_VENTA, ID_USUARIO, ESTADO
            FROM VENTAS
            WHERE ID_VENTA = ?
        ", [$id]);

        if (!$venta) {
            return back()->with('error', 'La venta no existe.');
        }

        if ($venta->estado == 'ANULADA') {
            return back()->with('error', 'La venta ya se encuentra anulada.');
        }

        if ($rol === 'VENDEDOR' && $venta->id_usuario != $idUsuario) {
            return back()->with('error', 'No puede anular ventas de otro usuario.');
        }

        DB::transaction(function () use ($id) {
            DB::statement("
                BEGIN
                    SP_DEL_VENTA(?);
                END;
            ", [$id]);
        });

        return redirect()
            ->route('ventas.index')
            ->with('success', 'Venta anulada correctamente.');

    } catch (\Exception $e) {
        return back()
            ->with('error', 'No se pudo anular la venta.');
    }
    
  }

}