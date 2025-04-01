<?php

namespace App\Http\Controllers;

use App\Models\MovCrecheAssociado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class MovCrecheAssociadoController extends Controller
{
    // Retorna apenas registros ativos (não deletados)
    public function index(): JsonResponse {
        $creches = MovCrecheAssociado::join('users', 'users.id', '=', 'mov_creches_associados.associado')
            ->whereNull('mov_creches_associados.deleted_at')
            ->select(
                'mov_creches_associados.*',   
                'users.NOME as associado_name'
            )
            ->get()
            ->map(function ($creche) {
                if ($creche->datahora && $creche->datahora !== '0000-00-00 00:00:00') {
                    $creche->datahora = Carbon::parse($creche->datahora)->format('d/m/Y H:i');
                } else {
                    $creche->datahora = null; 
                }
                return $creche;
            });
        return response()->json($creches);
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
        
        $registro->datahora = ($registro->datahora && $registro->datahora !== '0000-00-00 00:00:00') 
            ? Carbon::parse($registro->datahora)->format('d/m/Y H:i') 
            : null;
        
        $registro->data_inicio = ($registro->data_inicio && $registro->data_inicio !== '0000-00-00 00:00:00') 
            ? Carbon::parse($registro->data_inicio)->format('d/m/Y H:i') 
            : null;
        
        $registro->data_termino = ($registro->data_termino && $registro->data_termino !== '0000-00-00 00:00:00') 
            ? Carbon::parse($registro->data_termino)->format('d/m/Y H:i') 
            : null;

            $registro->lancamento = ($registro->lancamento && $registro->lancamento !== '0000-00-00 00:00:00') 
            ? Carbon::parse($registro->lancamento)->format('d/m/Y H:i') 
            : null;
        

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
