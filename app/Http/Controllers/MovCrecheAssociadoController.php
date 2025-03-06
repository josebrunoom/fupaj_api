<?php

namespace App\Http\Controllers;

use App\Models\MovCrecheAssociado;
use Illuminate\Http\Request;

class MovCrecheAssociadoController extends Controller
{
    public function index()
    {
        return response()->json(MovCrecheAssociado::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'associado' => 'required|integer',
            'tipo' => 'nullable|string|max:10',
            'parcelas' => 'nullable|integer',
            'data_inicio' => 'nullable|date',
            'data_termino' => 'nullable|date',
            'lancamento' => 'nullable|date',
            'observacao' => 'nullable|string|max:500',
            'status' => 'nullable|string|max:10',
            'usuario' => 'nullable|string|max:10',
            'datahora' => 'nullable|date'
        ]);

        $registro = MovCrecheAssociado::create($data);
        return response()->json($registro, 201);
    }

    public function show($id)
    {
        return response()->json(MovCrecheAssociado::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'associado' => 'required|integer',
            'tipo' => 'nullable|string|max:10',
            'parcelas' => 'nullable|integer',
            'data_inicio' => 'nullable|date',
            'data_termino' => 'nullable|date',
            'lancamento' => 'nullable|date',
            'observacao' => 'nullable|string|max:500',
            'status' => 'nullable|string|max:10',
            'usuario' => 'nullable|string|max:10',
            'datahora' => 'nullable|date'
        ]);

        $registro = MovCrecheAssociado::findOrFail($id);
        $registro->update($data);
        return response()->json($registro);
    }

    public function destroy($id)
    {
        $registro = MovCrecheAssociado::findOrFail($id);
        $registro->delete();
        return response()->json(null, 204);
    }
}
