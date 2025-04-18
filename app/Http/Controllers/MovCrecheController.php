<?php

namespace App\Http\Controllers;

use App\Models\MovCreche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class MovCrecheController extends Controller
{
    public function index(): JsonResponse
    {
        $creches = MovCreche::join('users', 'users.id', '=', 'mov_creches.associado') 
            ->select(
                'mov_creches.*',   
                'users.NOME as associado_name'
            )
            ->whereNull('mov_creches.deleted_at')
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
        return response()->json(MovCreche::onlyTrashed()->get());
    }

    // Criação de um novo registro
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

    // Retorna um registro específico, incluindo deletados
    public function show($id)
    {
        $registro = MovCreche::withTrashed()->find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        if ($registro->datahora && $registro->datahora !== '0000-00-00 00:00:00') {
            $registro->datahora = Carbon::parse($registro->datahora)->format('d/m/Y H:i');
        } else {
            $registro->datahora = null;
        }

        return response()->json($registro);
    }

    // Atualiza um registro (mesmo que esteja deletado)
    public function update(Request $request, $id)
    {
        $registro = MovCreche::withTrashed()->find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

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

        $registro->update($data);
        return response()->json($registro);
    }

    public function destroy(Request $request, $id){
        $registro = MovCreche::withTrashed()->find($id);

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
        $registro = MovCreche::onlyTrashed()->find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro não encontrado ou já restaurado'], 404);
        }

        $registro->restore();
        $registro->update(['observacao_delete' => null]); // Limpa a observação ao restaurar

        return response()->json(['message' => 'Registro restaurado com sucesso'], 200);
    }

}
