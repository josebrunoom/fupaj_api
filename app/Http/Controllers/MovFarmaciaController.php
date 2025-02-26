<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovFarmacia;

class MovFarmaciaController extends Controller
{
    public function index()
    {
        $mov_farmacia = MovFarmacia::join('users','users.id','=','mov_farmacia.associado')
        ->join('farmacias','farmacias.codigo','=','mov_farmacia.farmacia')
        ->select('mov_farmacia.*', 'users.name as nome_usuario', 'farmacias.nome as nome_farmacia')
        ->get();

        return response()->json($mov_farmacia);
    }

    public function store(Request $request)
    {
        $request->validate([
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
        ]);

        $mov_farmacia = MovFarmacia::create($request->all());
        return response()->json($mov_farmacia, 201);
    }

    public function show($id)
    {
        $mov_farmacia = MovFarmacia::join('users','users.id','=','mov_farmacia.associado')
        ->join('farmacias','farmacias.codigo','=','mov_farmacia.farmacia')
        ->select('mov_farmacia.*', 'users.name as nome_usuario', 'farmacias.nome as nome_farmacia')
        ->where('mov_farmacia.id',$id)
        ->get();
        
        return response()->json($mov_farmacia);
    }

    public function update(Request $request, $id)
    {
        $mov_farmacia = MovFarmacia::findOrFail($id);
        $mov_farmacia->update($request->all());
        return response()->json($mov_farmacia);
    }

    public function destroy($id)
    {
        MovFarmacia::destroy($id);
        return response()->json(['message' => 'Movimentação excluída com sucesso']);
    }
}
