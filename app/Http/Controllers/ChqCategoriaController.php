<?php

namespace App\Http\Controllers;

use App\Models\ChqCategoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChqCategoriaController extends Controller
{
    /**
     * Listar todas as categorias.
     */
    public function index(): JsonResponse
    {
        return response()->json(ChqCategoria::all());
    }

    /**
     * Criar uma nova categoria.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'descricao' => 'nullable|string|max:200',
            'gravarchq_cursos' => 'nullable|string|max:3',
            'gravarchq_oticas' => 'nullable|string|max:3',
            'usuario' => 'nullable|string|max:10',
            'datahora' => 'nullable|date',
        ]);

        $categoria = ChqCategoria::create($request->all());

        return response()->json($categoria, 201);
    }

    /**
     * Exibir uma categoria específica.
     */
    public function show($id): JsonResponse
    {
        $categoria = ChqCategoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        return response()->json($categoria);
    }

    /**
     * Atualizar uma categoria.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $categoria = ChqCategoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        $categoria->update($request->all());

        return response()->json($categoria);
    }

    /**
     * Deletar uma categoria.
     */
    public function destroy($id): JsonResponse
    {
        $categoria = ChqCategoria::find($id);

        if (!$categoria) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        $categoria->delete();

        return response()->json(['message' => 'Registro excluído com sucesso']);
    }
}
