<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index()
    {
        $compras = DB::select("
            SELECT
                ID_COMPRA,
                FECHA_COMPRA,
                NOMBRE_PROVEEDOR,
                USUARIO_REGISTRA,
                NOMBRE_PRODUCTO,
                CANTIDAD,
                PRECIO_UNITARIO,
                SUBTOTAL,
                TOTAL,
                ESTADO
            FROM VW_COMPRAS_DETALLE
            ORDER BY ID_COMPRA
        ");

        return view('compras.index', compact('compras'));
    }

    public function create()
    {
        $proveedores = DB::select("
            SELECT ID_PROVEEDOR, NOMBRE_PROVEEDOR
            FROM PROVEEDORES
            WHERE ESTADO = 'A'
            ORDER BY NOMBRE_PROVEEDOR
        ");

        $productos = DB::select("
            SELECT ID_PRODUCTO, NOMBRE_PRODUCTO, PRECIO_COMPRA, STOCK
            FROM PRODUCTOS
            WHERE ESTADO = 'A'
            ORDER BY NOMBRE_PRODUCTO
        ");

        return view('compras.create', compact('proveedores', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_proveedor' => 'required',
            'id_producto' => 'required',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
        ]);

        $idUsuario = session('usuario.id_usuario');

        try {
            DB::transaction(function () use ($request, $idUsuario) {
                DB::statement("
                    DECLARE
                        V_ID_COMPRA NUMBER;
                    BEGIN
                        SP_INS_COMPRA(?, ?, V_ID_COMPRA);

                        SP_INS_DETALLE_COMPRA(
                            V_ID_COMPRA,
                            ?,
                            ?,
                            ?
                        );
                    END;
                ", [
                    $request->id_proveedor,
                    $idUsuario,
                    $request->id_producto,
                    $request->cantidad,
                    $request->precio_unitario,
                ]);
            });

            return redirect()
                ->route('compras.index')
                ->with('success', 'Compra registrada correctamente.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'No se pudo registrar la compra. Revise los datos ingresados.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $compra = DB::selectOne("
                SELECT ID_COMPRA, ESTADO
                FROM COMPRAS
                WHERE ID_COMPRA = ?
            ", [$id]);

            if (!$compra) {
                return back()->with('error', 'La compra no existe.');
            }

            if ($compra->estado == 'ANULADA') {
                return back()->with('error', 'La compra ya se encuentra anulada.');
            }

            DB::transaction(function () use ($id) {
                DB::statement("
                    BEGIN
                        SP_DEL_COMPRA(?);
                    END;
                ", [$id]);
            });

            return redirect()
                ->route('compras.index')
                ->with('success', 'Compra anulada correctamente.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'No se pudo anular la compra. Verifique el stock disponible.');
        }
    }
}