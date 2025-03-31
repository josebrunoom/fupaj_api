<?php

namespace App\Http\Controllers;

use App\Models\ChqCategoriaAssociado;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;


class ChqCategoriaAssociadoController extends Controller
{
    public function index(): JsonResponse{
        $cheques = ChqCategoriaAssociado::join('users', 'users.id', '=', 'chq_categorias_associado.ASSOCIADO') 
            ->join('chq_categorias', 'chq_categorias.id', '=', 'chq_categorias_associado.categoria') 
            ->select(
                'chq_categorias_associado.*',   
                'users.NOME as associado_nome', // Nome do associado
                'chq_categorias.descricao as nome_categoria' // Nome da categoria
            )
            ->get()
            ->map(function ($cheque) {
                if ($cheque->datahora && $cheque->datahora !== '0000-00-00 00:00:00') {
                    $cheque->datahora = Carbon::parse($cheque->datahora)->format('d/m/Y H:i');
                } else {
                    $cheque->datahora = null; 
                }
                return $cheque;
            });;

        return response()->json($cheques);
    }

    public function store(Request $request){
        $data = $request->validate([
            'categoria' => 'required|integer',
            'associado' => 'required|integer',
            'usuario' => 'nullable|string|max:10',
            'datahora' => 'nullable|date'
        ]);

        $registroExistente = ChqCategoriaAssociado::where('categoria', $data['categoria'])
            ->where('associado', $data['associado'])
            ->first();

        if ($registroExistente) {
            $registroExistente->delete();
            return response()->json(['message' => 'categoria removida com sucesso'], 200);
        } 

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
