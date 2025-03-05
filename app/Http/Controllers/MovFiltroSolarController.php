<?php

namespace App\Http\Controllers;
use App\Models\MovFiltroSolar;

use Illuminate\Http\Request;

class MovFiltroSolarController extends Controller
{
    public function index()
    {
        return response()->json(MovFiltroSolar::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'associado' => 'required|integer',
            'farmacia' => 'nullable|integer',
            'numnota' => 'nullable|integer',
            'dataemissao' => 'nullable|date',
            'lancamento' => 'nullable|date',
            'valorfiltro' => 'nullable|numeric',
            'valorfundacao' => 'nullable|numeric',
            'valorassociado' => 'nullable|numeric',
            'usuario' => 'nullable|string|max:10',
            'datahora' => 'nullable|date',
            'cancelamento' => 'nullable|string|max:3',
        ]);

        $registro = MovFiltroSolar::create($data);
        return response()->json($registro, 201);
    }

    public function show($id)
    {
        $registro = MovFiltroSolar::find($id);
        if (!$registro) return response()->json(['error' => 'Registro não encontrado'], 404);
        return response()->json($registro);
    }

    public function update(Request $request, $id)
    {
        $registro = MovFiltroSolar::find($id);
        if (!$registro) return response()->json(['error' => 'Registro não encontrado'], 404);

        $data = $request->validate([
            'associado' => 'sometimes|integer',
            'farmacia' => 'nullable|integer',
            'numnota' => 'nullable|integer',
            'dataemissao' => 'nullable|date',
            'lancamento' => 'nullable|date',
            'valorfiltro' => 'nullable|numeric',
            'valorfundacao' => 'nullable|numeric',
            'valorassociado' => 'nullable|numeric',
            'usuario' => 'nullable|string|max:10',
            'datahora' => 'nullable|date',
            'cancelamento' => 'nullable|string|max:3',
        ]);

        $registro->update($data);
        return response()->json($registro);
    }

    public function destroy($id)
    {
        $registro = MovFiltroSolar::find($id);
        if (!$registro) return response()->json(['error' => 'Registro não encontrado'], 404);

        $registro->delete();
        return response()->json(['message' => 'Registro deletado']);
    }
}
