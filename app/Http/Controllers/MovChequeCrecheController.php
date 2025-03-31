<?php

namespace App\Http\Controllers;
use App\Models\ChqCategorias;

use App\Models\MovChequeCreche;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class MovChequeCrecheController extends Controller
{
    // Exibir todos os registros
    public function index(): JsonResponse{
        $cheques = MovChequeCreche::join('users', 'users.id', '=', 'mov_chequescreches.ASSOCIADO') 
            ->join('chq_categorias', 'chq_categorias.id', '=', 'mov_chequescreches.categoria') 
            ->select(
                'mov_chequescreches.*',   
                'users.NOME as associado_nome',
                'chq_categorias.descricao as nome_categoria'
            )
            ->get();
    
        return response()->json($cheques);
    }
    
    // Criar um novo registro
    public function store(Request $request)
    {
        $request->validate([
            'NUMCHEQUE' => 'nullable|integer',
            'VALOR' => 'nullable|numeric',
            'CATEGORIA' => 'nullable|integer',
            'IMPRESSO' => 'nullable|boolean',
            'CANCELADO' => 'nullable|boolean',
            'DATA' => 'nullable|date',
            'NOMINAL' => 'nullable|string|max:50',
            'DATACADASTRO' => 'nullable|date',
            'ASSOCIADO' => 'nullable|integer',
            'OBSERVACAO' => 'nullable|string|max:200',
            'USUARIO' => 'nullable|string|max:10',
            'DATAHORA' => 'nullable|date',
            'ENVIARITAU' => 'nullable|string|max:10',
            'NOME_TXT_ITAU' => 'nullable|string|max:50',
            'OBSERVACAO1' => 'nullable|string|max:200',
        ]);

        $movChequeCreche = MovChequeCreche::create($request->all());
        return response()->json($movChequeCreche, 201);
    }

    // Exibir um registro específico
    public function show($id)
    {
        $movChequeCreche = MovChequeCreche::find($id);

        if (!$movChequeCreche) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        return response()->json($movChequeCreche);
    }

    public function update(Request $request, $id): JsonResponse
    {
    $movChequeCreche = MovChequeCreche::find($id);

    if (!$movChequeCreche) {
        return response()->json(['message' => 'Registro não encontrado'], 404);
    }

    $request->validate([
        'NUMCHEQUE' => 'nullable|integer',
        'VALOR' => 'nullable|numeric',
        'CATEGORIA' => 'nullable|integer',
        'IMPRESSO' => 'required|boolean',
        'CANCELADO' => 'required|boolean',
        'DATA' => 'nullable|date',
        'NOMINAL' => 'nullable|string|max:50',
        'DATACADASTRO' => 'nullable|date',
        'ASSOCIADO' => 'nullable|integer',
        'OBSERVACAO' => 'nullable|string|max:200',
        'USUARIO' => 'nullable|string|max:10',
        'DATAHORA' => 'nullable|date',
        'ENVIARITAU' => 'nullable|string|max:10',
        'NOME_TXT_ITAU' => 'nullable|string|max:50',
        'OBSERVACAO1' => 'nullable|string|max:200',
    ]);

    $movChequeCreche->update($request->all());

    return response()->json(['message' => 'Registro atualizado com sucesso', 'data' => $movChequeCreche]);
}


    // Deletar um registro
    public function destroy(MovChequeCreche $movChequeCreche)
    {
        $movChequeCreche->delete();
        return response()->json(['message' => 'MovChequeCreche deleted successfully']);
    }
}
