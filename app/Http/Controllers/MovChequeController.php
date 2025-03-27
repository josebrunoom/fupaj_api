<?php

namespace App\Http\Controllers;

use App\Models\MovCheque;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MovChequeController extends Controller
{
    public function index(): JsonResponse
    {
        $cheques = MovCheque::join('users', 'users.id', '=', 'mov_cheques.associado') 
            ->join('chq_categorias', 'chq_categorias.id', '=', 'mov_cheques.categoria') 
            ->select(
                'mov_cheques.*',   
                'users.NOME as associado_name',
                'chq_categorias.descricao as categoria_name' 
            )
            ->get();

        return response()->json($cheques);
    }



    public function store(Request $request)
    {
        try {
            $request->validate([
                'numcheque' => 'nullable|integer',
                'valor' => 'nullable|numeric',
                'categoria' => 'nullable|numeric',
                'impresso' => 'boolean',
                'cancelado' => 'boolean',
                'data' => 'nullable|date',
                'nominal' => 'nullable|string|max:50',
                'datacadastro' => 'nullable|date',
                'associado' => 'nullable|numeric',
                'observacao' => 'nullable|string|max:200',
                'usuario' => 'nullable|string|max:10',
                'datahora' => 'nullable|date',
                'enviaritau' => 'nullable|string|max:10',
                'nome_txt_itau' => 'nullable|string|max:50',
            ]);
            
            return response()->json(MovCheque::create($request->all()), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function show(MovCheque $movCheque)
    {
        return response()->json($movCheque);
    }

    public function update(Request $request, MovCheque $movCheque)
    {
        $request->validate([
            'numcheque' => 'nullable|integer',
            'valor' => 'nullable|numeric',
            'categoria' => 'nullable|numeric',
            'impresso' => 'boolean',
            'cancelado' => 'boolean',
            'data' => 'nullable|date',
            'nominal' => 'nullable|string|max:50',
            'datacadastro' => 'nullable|date',
            'associado' => 'nullable|numeric',
            'observacao' => 'nullable|string|max:200',
            'usuario' => 'nullable|string|max:10',
            'datahora' => 'nullable|date',
            'enviaritau' => 'nullable|string|max:10',
            'nome_txt_itau' => 'nullable|string|max:50',
        ]);

        $movCheque->update($request->all());
        return response()->json($movCheque);
    }

    public function destroy(MovCheque $movCheque)
    {
        $movCheque->delete();
        return response()->json(['message' => 'MovCheque deletado com sucesso']);
    }
}
