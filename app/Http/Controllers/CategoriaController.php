<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = DB::select("
            SELECT
                ID_CATEGORIA,
                NOMBRE_CATEGORIA,
                DESCRIPCION,
                ESTADO
            FROM CATEGORIAS
            ORDER BY ID_CATEGORIA
        ");

        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_categoria' => 'required|max:80',
            'descripcion' => 'nullable|max:200',
        ], [
            'nombre_categoria.required' => 'Debe ingresar el nombre de la categoría.',
            'nombre_categoria.max' => 'El nombre no puede superar los 80 caracteres.',
            'descripcion.max' => 'La descripción no puede superar los 200 caracteres.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                DB::statement("
                    BEGIN
                        SP_INS_CATEGORIA(?, ?);
                    END;
                ", [
                    $request->nombre_categoria,
                    $request->descripcion,
                ]);
            });

if ($request->volver == 'producto') {
    return redirect()
        ->route('inventario.create')
        ->with('success', 'Categoría registrada correctamente. Ahora puede seleccionar la nueva categoría.');
}

return redirect()
    ->route('categorias.index')
    ->with('success', 'Categoría registrada correctamente.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'No se pudo registrar la categoría. Revise los datos ingresados.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $categoria = DB::selectOne("
            SELECT
                ID_CATEGORIA,
                NOMBRE_CATEGORIA,
                DESCRIPCION,
                ESTADO
            FROM CATEGORIAS
            WHERE ID_CATEGORIA = ?
        ", [$id]);

        if (!$categoria) {
            abort(404);
        }

        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_categoria' => 'required|max:80',
            'descripcion' => 'nullable|max:200',
            'estado' => 'required|in:A,I',
        ], [
            'nombre_categoria.required' => 'Debe ingresar el nombre de la categoría.',
            'nombre_categoria.max' => 'El nombre no puede superar los 80 caracteres.',
            'descripcion.max' => 'La descripción no puede superar los 200 caracteres.',
            'estado.required' => 'Debe seleccionar un estado.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                DB::statement("
                    BEGIN
                        SP_UPD_CATEGORIA(?, ?, ?, ?);
                    END;
                ", [
                    $id,
                    $request->nombre_categoria,
                    $request->descripcion,
                    $request->estado,
                ]);
            });

            return redirect()
                ->route('categorias.index')
                ->with('success', 'Categoría actualizada correctamente.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'No se pudo actualizar la categoría. Revise los datos ingresados.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                DB::statement("
                    BEGIN
                        SP_DEL_CATEGORIA(?);
                    END;
                ", [$id]);
            });

            return redirect()
                ->route('categorias.index')
                ->with('success', 'Categoría desactivada correctamente.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'No se pudo desactivar la categoría.');
        }
    }
}