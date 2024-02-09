<?php

namespace App\Http\Controllers;
use App\Models\Cliente;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return response()->json($clientes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email',
            'celular' => 'required|string|max:20',
        ]);

        $cliente = Cliente::create($request->all());

        return response()->json($cliente, 201);
    }

    public function show($id)
    {
        $cliente = Cliente::find($id);
        return response()->json($cliente);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email,' . $id,
            'celular' => 'required|string|max:20',
        ]);

        $cliente = Cliente::find($id);
        $cliente->update($request->all());

        return response()->json($cliente, 200);
    }

    public function destroy($id)
    {
        $cliente = Cliente::find($id);
        $cliente->delete();

        return response()->json(null, 204);
    }

    public function reporteClientesConLibrosVencidos()
{
    // Obtener los clientes con libros vencidos
    return $clientesConLibrosVencidos = Cliente::whereHas('prestamos', function ($query) {
        $query->whereDate('fecha_prestamo', '<', now()->subDays(DB::raw('dias_prestamo')));
    })->get();

    // Puedes personalizar cómo presentar la información, por ejemplo, en formato de tabla
    $reporte = [];
    foreach ($clientesConLibrosVencidos as $cliente) {
        $reporte[] = [
            'Cliente' => $cliente->nombre,
            'Libros Vencidos' => $cliente->librosVencidos()->count(),
            // Puedes agregar más información si es necesario
        ];
    }

    return response()->json($reporte);
}
}
