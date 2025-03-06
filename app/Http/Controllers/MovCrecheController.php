<?php

namespace App\Http\Controllers;

use App\Models\MovCreche;
use Illuminate\Http\Request;

class MovCrecheController extends Controller
{
    public function index()
    {
        return response()->json(MovCreche::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'associado' => 'required|integer',
            'lancamento' => 'nullable|date',
            'pagamento' => 'nullable|date',
            'valor' => 'nullable|numeric',
            'observacao' => 'nullable|string|max:500',
            'tipo' => 'nullable|string|max:10',
            'usuario' => 'nullable|string|max:10',
            'datahora' => 'nullable|date',
            'lancto_cheque' => 'nullable|integer',
            'cancel_cheque' => 'nullable|string|max:10',
            'controle' => 'nullable|integer'
        ]);

        $registro = MovCreche::create($data);
        return response()->json($registro, 201);
    }

    public function show($id)
    {
        return response()->json(MovCreche::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'associado' => 'required|integer',
            'lancamento' => 'nullable|date',
            'pagamento' => 'nullable|date',
            'valor' => 'nullable|numeric',
            'observacao' => 'nullable|string|max:500',
            'tipo' => 'nullable|string|max:10',
            'usuario' => 'nullable|string|max:10',
            'datahora' => 'nullable|date',
            'lancto_cheque' => 'nullable|integer',
            'cancel_cheque' => 'nullable|string|max:10',
            'controle' => 'nullable|integer'
        ]);

        $registro = MovCreche::findOrFail($id);
        $registro->update($data);
        return response()->json($registro);
    }

    public function destroy($id)
    {
        $registro = MovCreche::findOrFail($id);
        $registro->delete();
        return response()->json(null, 204);
    }
}
