<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farmacia;

class FarmaciaController extends Controller
{
    public function index()
    {
        return response()->json(Farmacia::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'cnpj' => 'nullable|string|max:30',
            'nome' => 'nullable|string|max:50',
            'endereco' => 'nullable|string|max:50',
            'bairro' => 'nullable|string|max:21',
            'cidade' => 'nullable|string|max:21',
            'uf' => 'nullable|string|max:3',
            'cep' => 'nullable|string|max:15',
            'telefone' => 'nullable|string|max:15',
            'fax' => 'nullable|string|max:20',
            'inscricao_estadual' => 'nullable|string|max:20',
            'usuario' => 'nullable|string|max:10',
            'data_hora' => 'nullable|date',
        ]);

        $farmacia = Farmacia::create($request->all());
        return response()->json($farmacia, 201);
    }

    public function show($id)
    {
        $farmacia = Farmacia::findOrFail($id);
        return response()->json($farmacia);
    }

    public function update(Request $request, $id)
    {
        $farmacia = Farmacia::findOrFail($id);
        $farmacia->update($request->all());
        return response()->json($farmacia);
    }

    public function destroy($id)
    {
        Farmacia::destroy($id);
        return response()->json(['message' => 'Farmácia excluída com sucesso']);
    }
}

