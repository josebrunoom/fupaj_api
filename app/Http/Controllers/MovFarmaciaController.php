<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovFarmacia;

class MovFarmaciaController extends Controller
{
    // Retorna apenas registros ativos (não deletados)
    public function index()
    {
        $mov_farmacia = MovFarmacia::whereNull('deleted_at')
            ->join('users', 'users.id', '=', 'mov_farmacia.associado')
            ->join('farmacias', 'farmacias.codigo', '=', 'mov_farmacia.farmacia')
            ->select('mov_farmacia.*', 'users.NOME as nome_usuario', 'farmacias.nome as nome_farmacia')
            ->get();

        return response()->json($mov_farmacia);
    }

    // Retorna apenas registros deletados (soft deleted)
    public function trashed()
    {
        return response()->json(MovFarmacia::onlyTrashed()->get());
    }

    // Criação de um novo registro
    public function store(Request $request)
    {
        $data = $request->validate([
            'numnota' => 'nullable|numeric',
            'farmacia' => 'nullable|numeric',
            'associado' => 'nullable|numeric',
            'valornota' => 'nullable|numeric|between:0,999999999999999999.99',
            'valorfundacao' => 'nullable|numeric|between:0,999999999999999999.99',
            'valorassociado' => 'nullable|numeric|between:0,999999999999999999.99',
            'lancamento' => 'nullable|date',
            'emissao' => 'nullable|date',
            'receita_sn' => 'nullable|string|max:3',
            'usuario' => 'nullable|string|max:10',
            'datahora' => 'nullable|date',
            'pdf' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        if ($request->hasFile('pdf')) {
            $file = $request->file('pdf');
            $filePath = $file->store('pdfs', 'public'); // Salva em storage/app/public/pdfs
            $data['pdf_path'] = $filePath;
        }

        $mov_farmacia = MovFarmacia::create($data);
        return response()->json($mov_farmacia, 201);
    }

    // Retorna um registro específico, incluindo deletados
    public function show($id)
    {
        $mov_farmacia = MovFarmacia::
            join('users', 'users.id', '=', 'mov_farmacia.associado')
            ->join('farmacias', 'farmacias.codigo', '=', 'mov_farmacia.farmacia')
            ->select('mov_farmacia.*', 'users.NOME as nome_usuario', 'farmacias.nome as nome_farmacia')
            ->where('mov_farmacia.id', $id)
            ->first();

        if (!$mov_farmacia) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        return response()->json($mov_farmacia);
    }

    public function update(Request $request, $id){
        $mov_farmacia = MovFarmacia::find($id);

        if (!$mov_farmacia) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        $data = $request->validate([
            'numnota' => 'nullable|numeric',
            'farmacia' => 'nullable|numeric',
            'associado' => 'nullable|numeric',
            'valornota' => 'nullable|numeric|between:0,999999999999999999.99',
            'valorfundacao' => 'nullable|numeric|between:0,999999999999999999.99',
            'valorassociado' => 'nullable|numeric|between:0,999999999999999999.99',
            'lancamento' => 'nullable|date',
            'emissao' => 'nullable|date',
            'receita_sn' => 'nullable|string|max:3',
            'usuario' => 'nullable|string|max:10',
            'datahora' => 'nullable|date',
            'pdf' => 'nullable|file|mimes:pdf|max:2048' // Apenas PDFs até 2MB
        ]);

        // Verifica se um novo PDF foi enviado
        if ($request->hasFile('pdf')) {
            // Remove o arquivo antigo, se existir
            if ($mov_farmacia->pdf_path) {
                Storage::disk('public')->delete($mov_farmacia->pdf_path);
            }

            // Salva o novo arquivo e atualiza o caminho
            $file = $request->file('pdf');
            $filePath = $file->store('pdfs', 'public'); // Salva em storage/app/public/pdfs
            $data['pdf_path'] = $filePath;
        } else {
            // Mantém o PDF atual se não for enviado um novo
            $data['pdf_path'] = $mov_farmacia->pdf_path;
        }

        // Atualiza o registro no banco de dados
        $mov_farmacia->update($data);

        return response()->json($mov_farmacia);
    }

    // Soft delete (marca como deletado e adiciona observação)
    public function destroy(Request $request, $id)
    {
        $mov_farmacia = MovFarmacia::find($id);

        if (!$mov_farmacia) {
            return response()->json(['message' => 'Registro não encontrado'], 404);
        }

        if ($mov_farmacia->trashed()) {
            return response()->json(['message' => 'Este registro já foi deletado'], 400);
        }

        $request->validate([
            'observacao_delete' => 'required|string|max:500'
        ]);

        // Atualiza a observação antes de deletar
        $mov_farmacia->update(['observacao_delete' => $request->input('observacao_delete')]);
        $mov_farmacia->delete();

        return response()->json(['message' => 'Registro deletado com sucesso'], 200);
    }

    // Restaura um registro deletado
    public function restore($id)
    {
        $mov_farmacia = MovFarmacia::find($id);

        if (!$mov_farmacia) {
            return response()->json(['message' => 'Registro não encontrado ou já restaurado'], 404);
        }

        $mov_farmacia->restore();
        $mov_farmacia->update(['observacao_delete' => null]); // Limpa a observação ao restaurar

        return response()->json(['message' => 'Registro restaurado com sucesso'], 200);
    }

    // Retorna registros por farmácia
    public function showByFarmacia($id)
    {
        $mov_farmacia = MovFarmacia::whereNull('deleted_at')
            ->join('users', 'users.id', '=', 'mov_farmacia.associado')
            ->join('farmacias', 'farmacias.codigo', '=', 'mov_farmacia.farmacia')
            ->select('mov_farmacia.*', 'users.NOME as nome_usuario', 'farmacias.nome as nome_farmacia')
            ->where('mov_farmacia.farmacia', $id)
            ->get();

        return response()->json($mov_farmacia);
    }
}
