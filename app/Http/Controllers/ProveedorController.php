<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = DB::select("
            SELECT
                ID_PROVEEDOR,
                NOMBRE_PROVEEDOR,
                TELEFONO,
                CORREO,
                DIRECCION,
                ESTADO
            FROM PROVEEDORES
            ORDER BY ID_PROVEEDOR
        ");

        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {

    $request->validate([
    'nombre_proveedor' => 'required|max:100',
    'telefono' => 'nullable|regex:/^\d{4}-?\d{4}$/',
    'correo' => 'nullable|email|max:100',
    'direccion' => 'nullable|max:200',
], [
    'telefono.regex' => 'El teléfono debe tener 8 dígitos, por ejemplo 88889999 o 8888-9999.',
    'correo.email' => 'Debe ingresar un correo válido.',
]);

$telefono = $this->formatearTelefono($request->telefono);

        try {
            DB::transaction(function () use ($request, $telefono) {
                DB::statement("
                    BEGIN
                        SP_INS_PROVEEDOR(?, ?, ?, ?);
                    END;
                ", [
                    $request->nombre_proveedor,
                    $telefono,
                    $request->correo,
                    $request->direccion,
                ]);
            });

if ($request->volver == 'producto') {
    return redirect()
        ->route('inventario.create')
        ->with('success', 'Proveedor registrado correctamente. Ahora puede seleccionar el nuevo proveedor.');
}

return redirect()
    ->route('proveedores.index')
    ->with('success', 'Proveedor registrado correctamente.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'No se pudo registrar el proveedor. Revise los datos ingresados.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $proveedor = DB::selectOne("
            SELECT
                ID_PROVEEDOR,
                NOMBRE_PROVEEDOR,
                TELEFONO,
                CORREO,
                DIRECCION,
                ESTADO
            FROM PROVEEDORES
            WHERE ID_PROVEEDOR = ?
        ", [$id]);

        if (!$proveedor) {
            abort(404);
        }

        return view('proveedores.edit', compact('proveedor'));
    }

public function update(Request $request, $id)
{
    $request->validate([
        'nombre_proveedor' => 'required|max:100',
        'telefono' => 'nullable|regex:/^\d{4}-?\d{4}$/',
        'correo' => 'nullable|email|max:100',
        'direccion' => 'nullable|max:200',
        'estado' => 'required|in:A,I',
    ], [
        'telefono.regex' => 'El teléfono debe tener 8 dígitos, por ejemplo 88889999 o 8888-9999.',
        'correo.email' => 'Debe ingresar un correo válido.',
        'estado.required' => 'Debe seleccionar un estado.',
        'estado.in' => 'El estado seleccionado no es válido.',
    ]);

    $telefono = $this->formatearTelefono($request->telefono);

    try {
        DB::transaction(function () use ($request, $id, $telefono) {
            DB::statement("
                BEGIN
                    SP_UPD_PROVEEDOR(?, ?, ?, ?, ?, ?);
                END;
            ", [
                $id,
                $request->nombre_proveedor,
                $telefono,
                $request->correo,
                $request->direccion,
                $request->estado,
            ]);
        });

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente.');

    } catch (\Exception $e) {
        return back()
            ->with('error', 'No se pudo actualizar el proveedor. Revise los datos ingresados.')
            ->withInput();
    }
}

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                DB::statement("
                    BEGIN
                        SP_DEL_PROVEEDOR(?);
                    END;
                ", [$id]);
            });

            return redirect()
                ->route('proveedores.index')
                ->with('success', 'Proveedor desactivado correctamente.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'No se pudo desactivar el proveedor.');
        }
    }

    private function formatearTelefono($telefono)
{
    if (!$telefono) {
        return null;
    }

    $soloNumeros = preg_replace('/\D/', '', $telefono);

    if (strlen($soloNumeros) === 8) {
        return substr($soloNumeros, 0, 4) . '-' . substr($soloNumeros, 4, 4);
    }

    return $telefono;
}

}