<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        return response()->json(Empresa::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'cnpj' => 'nullable|string|size:14',
            'empresa' => 'nullable|string|max:50',
            'endereco' => 'nullable|string|max:50',
            'numero' => 'nullable|string|max:50',
            'complemento' => 'nullable|string|max:50',
            'bairro' => 'nullable|string|max:50',
            'cidade' => 'nullable|string|max:50',
            'cep' => 'nullable|string|max:50',
            'estado' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:50',
            'data_arquivo' => 'nullable|date',
            'nome_arquivo' => 'nullable|string|max:50',
            'lote' => 'nullable|integer',
            'banco' => 'nullable|string|max:50',
            'agencia' => 'nullable|string|max:50',
            'conta_corrente' => 'nullable|string|max:50',
            'digito' => 'nullable|string|max:50',
        ]);

        $empresa = Empresa::create($request->all());
        return response()->json($empresa, 201);
    }

    public function show(Empresa $empresa)
    {
        return response()->json($empresa);
    }

    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'cnpj' => 'nullable|string|size:14',
            'empresa' => 'nullable|string|max:50',
            'endereco' => 'nullable|string|max:50',
            'numero' => 'nullable|string|max:50',
            'complemento' => 'nullable|string|max:50',
            'bairro' => 'nullable|string|max:50',
            'cidade' => 'nullable|string|max:50',
            'cep' => 'nullable|string|max:50',
            'estado' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:50',
            'data_arquivo' => 'nullable|date',
            'nome_arquivo' => 'nullable|string|max:50',
            'lote' => 'nullable|integer',
            'banco' => 'nullable|string|max:50',
            'agencia' => 'nullable|string|max:50',
            'conta_corrente' => 'nullable|string|max:50',
            'digito' => 'nullable|string|max:50',
        ]);

        $empresa->update($request->all());
        return response()->json($empresa);
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return response()->json(null, 204);
    }
}
