<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = DB::select("
            SELECT
                ID_CLIENTE,
                NOMBRE_CLIENTE,
                CEDULA,
                TELEFONO,
                CORREO,
                DIRECCION,
                ESTADO,
                FECHA_REGISTRO
            FROM CLIENTES
            ORDER BY ID_CLIENTE
        ");

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
$request->validate([
    'nombre_cliente' => 'required|max:100',
    'cedula' => 'required|max:30',
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
                        SP_INS_CLIENTE(?, ?, ?, ?, ?);
                    END;
                ", [
                    $request->nombre_cliente,
                    $request->cedula,
                    $telefono,
                    $request->correo,
                    $request->direccion,
                ]);
            });

            return redirect()
                ->route('clientes.index')
                ->with('success', 'Cliente registrado correctamente.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'No se pudo registrar el cliente. Revise si la cédula o correo ya existen.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $cliente = DB::selectOne("
            SELECT
                ID_CLIENTE,
                NOMBRE_CLIENTE,
                CEDULA,
                TELEFONO,
                CORREO,
                DIRECCION,
                ESTADO
            FROM CLIENTES
            WHERE ID_CLIENTE = ?
        ", [$id]);

        if (!$cliente) {
            abort(404);
        }

        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {

$request->validate([
    'nombre_cliente' => 'required|max:100',
    'cedula' => 'required|max:30',
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
                        SP_UPD_CLIENTE(?, ?, ?, ?, ?, ?, ?);
                    END;
                ", [
                    $id,
                    $request->nombre_cliente,
                    $request->cedula,
                    $telefono,
                    $request->correo,
                    $request->direccion,
                    $request->estado,
                ]);
            });

            return redirect()
                ->route('clientes.index')
                ->with('success', 'Cliente actualizado correctamente.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'No se pudo actualizar el cliente. Revise los datos ingresados.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                DB::statement("
                    BEGIN
                        SP_DEL_CLIENTE(?);
                    END;
                ", [$id]);
            });

            return redirect()
                ->route('clientes.index')
                ->with('success', 'Cliente desactivado correctamente.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'No se pudo desactivar el cliente.');
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