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
            ORDER BY ID_COMPRA DESC
        ");

        $comprasAgrupadas = collect($compras)->groupBy('id_compra');

        return view('compras.index', compact('comprasAgrupadas'));
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
           SELECT
           ID_PRODUCTO,
           ID_PROVEEDOR,
           NOMBRE_PRODUCTO,
           PRECIO_COMPRA,
           STOCK
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
        'productos' => 'required|array|min:1',
        'productos.*' => 'required',
        'cantidades' => 'required|array|min:1',
        'cantidades.*' => 'required|integer|min:1',
    ], [
        'id_proveedor.required' => 'Debe seleccionar un proveedor.',
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
                    V_ID_COMPRA NUMBER;
                BEGIN
                    SP_INS_COMPRA(?, ?, V_ID_COMPRA);
                END;
            ", [
                $request->id_proveedor,
                $idUsuario,
            ]);

            $ultimaCompra = DB::selectOne("
                SELECT MAX(ID_COMPRA) AS ID_COMPRA
                FROM COMPRAS
                WHERE ID_PROVEEDOR = ?
                AND ID_USUARIO = ?
            ", [
                $request->id_proveedor,
                $idUsuario,
            ]);

            foreach ($request->productos as $index => $idProducto) {

                $producto = DB::selectOne("
                    SELECT
                        ID_PRODUCTO,
                        PRECIO_COMPRA
                    FROM PRODUCTOS
                    WHERE ID_PRODUCTO = ?
                    AND ID_PROVEEDOR = ?
                    AND ESTADO = 'A'
                ", [
                    $idProducto,
                    $request->id_proveedor,
                ]);

                if (!$producto) {
                    throw new \Exception('El producto no pertenece al proveedor seleccionado.');
                }

                DB::statement("
                    BEGIN
                        SP_INS_DETALLE_COMPRA(?, ?, ?, ?);
                    END;
                ", [
                    $ultimaCompra->id_compra,
                    $idProducto,
                    $request->cantidades[$index],
                    $producto->precio_compra,
                ]);
            }
        });

        return redirect()
            ->route('compras.index')
            ->with('success', 'Compra registrada correctamente.');

    } catch (\Exception $e) {
        return back()
            ->with('error', 'No se pudo registrar la compra. Verifique que los productos pertenezcan al proveedor seleccionado.')
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