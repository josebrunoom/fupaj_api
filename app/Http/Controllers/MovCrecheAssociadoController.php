<?php

namespace App\Http\Controllers;

use App\Models\MovCrecheAssociado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovCrecheAssociadoController extends Controller
{
    // Retorna apenas registros ativos (não deletados)
    public function index()
    {
        return response()->json(MovCrecheAssociado::whereNull('deleted_at')->get());
    }

    // Retorna apenas registros deletados (soft deleted)
    public function trashed()
    {
        return response()->json(MovCrecheAssociado::onlyTrashed()->get());
    }

    // Criação de um novo registro
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

    // Retorna um registro específico, incluindo deletados
    public function show($id)
    {
        $registro = MovCrecheAssociado::withTrashed()->find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        return response()->json($registro);
    }

    // Atualiza um registro (mesmo que esteja deletado)
    public function update(Request $request, $id)
    {
        $registro = MovCrecheAssociado::withTrashed()->find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

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

        $registro->update($data);
        return response()->json($registro);
    }

    public function destroy(Request $request, $id){
        $registro = MovCrecheAssociado::withTrashed()->find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        if ($registro->trashed()) {
            return response()->json(['message' => 'Este registro já foi deletado'], 400);
        }

        $request->validate([
            'observacao_delete' => 'required|string|max:500'
        ]);

        // Atualiza a observação antes de deletar
        $registro->update(['observacao_delete' => $request->input('observacao_delete')]);
        $registro->delete();

        return response()->json(['message' => 'Registro deletado com sucesso'], 200);
    }   
    // Restaura um registro deletado
    public function restore($id){
        $registro = MovCrecheAssociado::onlyTrashed()->find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro não encontrado ou já restaurado'], 404);
        }

        $registro->restore();
        $registro->update(['observacao_delete' => null]); // Limpa a observação ao restaurar

        return response()->json(['message' => 'Registro restaurado com sucesso'], 200);
    }
}
