<?php

namespace App\Http\Controllers;
use App\Models\Libro;

use Illuminate\Http\Request;

class LibroController extends Controller
{
    public function index()
    {
        $libros = Libro::all();
        return response()->json($libros);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor_id' => 'required|exists:autores,id',
            'lote' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $libro = Libro::create($request->all());

        return response()->json($libro, 201);
    }

    public function show($id)
    {
        $libro = Libro::find($id);
        return response()->json($libro);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'autor_id' => 'required|exists:autores,id',
            'lote' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $libro = Libro::find($id);
        $libro->update($request->all());

        return response()->json($libro, 200);
    }

    public function destroy($id)
    {
        $libro = Libro::find($id);
        $libro->delete();

        return response()->json(null, 204);
    }
}
