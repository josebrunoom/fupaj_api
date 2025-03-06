<?php

namespace App\Http\Controllers;

use App\Models\ChqCategoriaAssociado;
use Illuminate\Http\Request;

class ChqCategoriaAssociadoController extends Controller
{
    public function index()
    {
        return response()->json(ChqCategoriaAssociado::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'categoria' => 'required|integer',
            'associado' => 'required|integer',
            'usuario' => 'nullable|string|max:10',
            'datahora' => 'nullable|date'
        ]);

        $registro = ChqCategoriaAssociado::create($data);
        return response()->json($registro, 201);
    }

    public function show($id)
    {
        return response()->json(ChqCategoriaAssociado::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'categoria' => 'required|integer',
            'associado' => 'required|integer',
            'usuario' => 'nullable|string|max:10',
            'datahora' => 'nullable|date'
        ]);

        $registro = ChqCategoriaAssociado::findOrFail($id);
        $registro->update($data);
        return response()->json($registro);
    }

    public function destroy($id)
    {
        $registro = ChqCategoriaAssociado::findOrFail($id);
        $registro->delete();
        return response()->json(null, 204);
    }
}
