<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
public function index(Request $request)
{
    $categorias = DB::select("
        SELECT ID_CATEGORIA, NOMBRE_CATEGORIA
        FROM CATEGORIAS
        WHERE ESTADO = 'A'
        ORDER BY NOMBRE_CATEGORIA
    ");

    $proveedores = DB::select("
        SELECT ID_PROVEEDOR, NOMBRE_PROVEEDOR
        FROM PROVEEDORES
        WHERE ESTADO = 'A'
        ORDER BY NOMBRE_PROVEEDOR
    ");

    $sql = "
        SELECT
            P.ID_PRODUCTO,
            P.NOMBRE_PRODUCTO,
            C.NOMBRE_CATEGORIA,
            PR.NOMBRE_PROVEEDOR,
            P.PRECIO_COMPRA,
            P.PRECIO_VENTA,
            P.STOCK,
            P.STOCK_MINIMO,
            P.ESTADO
        FROM PRODUCTOS P
        INNER JOIN CATEGORIAS C ON P.ID_CATEGORIA = C.ID_CATEGORIA
        INNER JOIN PROVEEDORES PR ON P.ID_PROVEEDOR = PR.ID_PROVEEDOR
        WHERE 1 = 1
    ";

    $parametros = [];

    if ($request->filled('id_categoria')) {
        $sql .= " AND P.ID_CATEGORIA = ?";
        $parametros[] = $request->id_categoria;
    }

    if ($request->filled('id_proveedor')) {
        $sql .= " AND P.ID_PROVEEDOR = ?";
        $parametros[] = $request->id_proveedor;
    }

    if ($request->filled('estado')) {
        $sql .= " AND P.ESTADO = ?";
        $parametros[] = $request->estado;
    }

    $sql .= " ORDER BY P.ID_PRODUCTO";

    $productos = DB::select($sql, $parametros);

    return view('inventario.index', compact(
        'productos',
        'categorias',
        'proveedores'
    ));
}
    public function create()
    {
        $categorias = DB::select("
            SELECT ID_CATEGORIA, NOMBRE_CATEGORIA
            FROM CATEGORIAS
            WHERE ESTADO = 'A'
            ORDER BY NOMBRE_CATEGORIA
        ");

        $proveedores = DB::select("
            SELECT ID_PROVEEDOR, NOMBRE_PROVEEDOR
            FROM PROVEEDORES
            WHERE ESTADO = 'A'
            ORDER BY NOMBRE_PROVEEDOR
        ");

        return view('inventario.create', compact('categorias', 'proveedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_categoria' => 'required',
            'id_proveedor' => 'required',
            'nombre_producto' => 'required|max:100',
            'descripcion' => 'nullable|max:200',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0|gte:precio_compra',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                DB::statement("
                    BEGIN
                        SP_INS_PRODUCTO(?, ?, ?, ?, ?, ?, ?, ?);
                    END;
                ", [
                    $request->id_categoria,
                    $request->id_proveedor,
                    $request->nombre_producto,
                    $request->descripcion,
                    $request->precio_compra,
                    $request->precio_venta,
                    $request->stock,
                    $request->stock_minimo,
                ]);
            });

            return redirect()
                ->route('inventario.index')
                ->with('success', 'Producto registrado correctamente.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'No se pudo registrar el producto. Revise los datos ingresados.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $producto = DB::selectOne("
            SELECT *
            FROM PRODUCTOS
            WHERE ID_PRODUCTO = ?
        ", [$id]);

        if (!$producto) {
            abort(404);
        }

        $categorias = DB::select("
            SELECT ID_CATEGORIA, NOMBRE_CATEGORIA
            FROM CATEGORIAS
            WHERE ESTADO = 'A'
            ORDER BY NOMBRE_CATEGORIA
        ");

        $proveedores = DB::select("
            SELECT ID_PROVEEDOR, NOMBRE_PROVEEDOR
            FROM PROVEEDORES
            WHERE ESTADO = 'A'
            ORDER BY NOMBRE_PROVEEDOR
        ");

        return view('inventario.edit', compact('producto', 'categorias', 'proveedores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_categoria' => 'required',
            'id_proveedor' => 'required',
            'nombre_producto' => 'required|max:100',
            'descripcion' => 'nullable|max:200',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0|gte:precio_compra',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'estado' => 'required|in:A,I',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                DB::statement("
                    BEGIN
                        SP_UPD_PRODUCTO(?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
                    END;
                ", [
                    $id,
                    $request->id_categoria,
                    $request->id_proveedor,
                    $request->nombre_producto,
                    $request->descripcion,
                    $request->precio_compra,
                    $request->precio_venta,
                    $request->stock,
                    $request->stock_minimo,
                    $request->estado,
                ]);
            });

            return redirect()
                ->route('inventario.index')
                ->with('success', 'Producto actualizado correctamente.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'No se pudo actualizar el producto. Revise los datos ingresados.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                DB::statement("
                    BEGIN
                        SP_DEL_PRODUCTO(?);
                    END;
                ", [$id]);
            });

            return redirect()
                ->route('inventario.index')
                ->with('success', 'Producto desactivado correctamente.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'No se pudo desactivar el producto.');
        }
    }
}