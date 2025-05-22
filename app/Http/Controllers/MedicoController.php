<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use Illuminate\Http\Request;

class MedicoController extends Controller {
    public function index() {
        return response()->json(Medico::all());
    }

    public function store(Request $request) {
        $request->validate([
            'especialidade' => 'required|string|max:100',
            'nome' => 'required|string|max:150',
            'endereco' => 'required|string|max:200',
            'bairro' => 'required|string|max:100',
            'cidade' => 'required|string|max:100',
            'uf' => 'required|string|max:2',
            'cep' => 'required|string|max:9',
            'telefone' => 'required|string|max:20',
            'crm' => 'required|string|max:20|unique:medicos',
            'cpf' => 'required|string|max:14|unique:medicos',
            'cnpj' => 'nullable|string|max:18',
        ]);

        $medico = Medico::create($request->all());
        return response()->json($medico, 201);
    }

    public function show($id) {
        $medico = Medico::findOrFail($id);
        return response()->json($medico);
    }

    public function update(Request $request, $id) {
        $medico = Medico::findOrFail($id);

        $request->validate([
            'especialidade' => 'sometimes|string|max:100',
            'nome' => 'sometimes|string|max:150',
            'endereco' => 'sometimes|string|max:200',
            'bairro' => 'sometimes|string|max:100',
            'cidade' => 'sometimes|string|max:100',
            'uf' => 'sometimes|string|max:2',
            'cep' => 'sometimes|string|max:9',
            'telefone' => 'sometimes|string|max:20',
            'crm' => 'sometimes|string|max:20|unique:medicos,crm,' . $id,
            'cpf' => 'sometimes|string|max:14|unique:medicos,cpf,' . $id,
            'cnpj' => 'nullable|string|max:18',
        ]);

        $medico->update($request->all());
        return response()->json($medico);
    }

    public function destroy($id) {
        $medico = Medico::findOrFail($id);
        $medico->delete();
        return response()->json(['message' => 'Médico deletado com sucesso']);
    }
}