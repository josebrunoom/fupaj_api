<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MovCheque;
use App\Models\MovChequeCreche;
use App\Models\Farmacia;
use App\Models\MovFarmacia;
use App\Models\MovCreche;
use App\Models\MovCrecheAssociado;
use App\Models\ChqCategoria;
use App\Models\ChqCategoriaAssociado;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request) {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);

            $usuario = $request->only('email');

            // Checking duplicated information
            $duplicated_error = $this->checkDuplicatedInformation($usuario);

            if ($duplicated_error != null) {
                return $duplicated_error;
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),      
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json(['user' => $user, 'token' => $token]);
        }
        catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'exception'=>$e->getMessage(),
                "error" => "Não foi possível criar o usuário, tente novamente mais tarde",
            ], 500);
        }
    }

    public function getUsersNearAgeLimits() {
        try {
            $dateNow = Carbon::now();
            $dateLimit = $dateNow->copy()->addMonths(3);
    
            // Definição do intervalo de 3 meses
            $twentyOneStart = $dateNow->copy()->subYears(21)->toDateString();
            $twentyOneEnd = $dateLimit->copy()->subYears(21)->toDateString();
    
            $twentyFourStart = $dateNow->copy()->subYears(24)->toDateString();
            $twentyFourEnd = $dateLimit->copy()->subYears(24)->toDateString();
    
            $fortyStart = $dateNow->copy()->subYears(40)->toDateString();
            $fortyEnd = $dateLimit->copy()->subYears(40)->toDateString();
    
            $dependents = [
                '10-FILHOS', '11-FILHOS', '12-FILHOS', '13-FILHOS', '30-FILHAS',
                '31-FILHAS', '32-FILHAS', '34-FILHAS', '35-FILHAS', 
                'ENTEADO', 'ENTEADA', 'ENTADO (A)', 'ENTEADO (A)', 
                '60-OUTROS DEPENDENTES'
            ];
    
            $retorno = [];
    
            $aggregates = ['90-AGREGADOS'];
    
            // Dependentes entre 21 e 24 anos nos próximos 3 meses
            $dependentsQuery = User::whereIn('PARENTESCO', $dependents)
                ->whereBetween('NASCIMENTO', [$twentyFourStart, $twentyFourEnd])
                ->orWhereBetween('NASCIMENTO', [$twentyOneStart, $twentyOneEnd])
                ->select('id', 'NOME', 'CPF', 'NASCIMENTO', 'PARENTESCO');
    
            // Agregados que completam 40 anos nos próximos 3 meses
            $aggregatesQuery = User::whereIn('PARENTESCO', $aggregates)
                ->whereBetween('NASCIMENTO', [$fortyStart, $fortyEnd])
                ->select('id', 'NOME', 'CPF', 'NASCIMENTO', 'PARENTESCO');
    
            // Une as consultas
            $retorno['dependents'] = $dependentsQuery->union($aggregatesQuery)->get();
    
            $retorno['users'] = User::all()->count();
            $retorno['associados'] = User::where('PARENTESCO', '00-TITULAR')->count();
            $retorno['instituicoes'] = Farmacia::all()->count();
            $retorno['beneficios'] = 
                                    MovCheque::count() +
                                    MovChequeCreche::count() +
                                    MovCreche::count() +
                                    MovCrecheAssociado::count() +
                                    ChqCategoria::count() +
                                    ChqCategoriaAssociado::count();
                                    
            return response()->json($retorno);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar usuários', 'message' => $e->getMessage()], 500);
        }
    }  
    
    public function getAssociados()
    {
        $users = User::where('PARENTESCO', '00-TITULAR')->get();
        return response()->json(['associados' => $users]);
    }

    public function getDependentes()
    {
        $dependents = [
            '10-FILHOS', '11-FILHOS', '12-FILHOS', '13-FILHOS', '30-FILHAS',
            '31-FILHAS', '32-FILHAS', '34-FILHAS', '35-FILHAS', 
            'ENTEADO', 'ENTEADA', 'ENTADO (A)', 'ENTEADO (A)', 
            '60-OUTROS DEPENDENTES'
        ];

        $users = User::whereIn('PARENTESCO', $dependents)->get();
        return response()->json(['dependentes' => $users]);
    }

    public function getAgregados()
    {
        $aggregates = ['90-AGREGADOS'];
        $users = User::whereIn('PARENTESCO', $aggregates)->get();
        return response()->json(['agregados' => $users]);
    }

    public function getLancamentos(Request $request){
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado'], 401);
        }

        $lancamentos = [];

        if ($user->role == 1) {

            $lancamentos = [
                'farmacia' => MovFarmacia::where('associado', $user->id)
                    ->join('farmacias', 'mov_farmacia.farmacia', '=', 'farmacias.codigo') 
                    ->select('mov_farmacia.*', 'farmacias.nome as farmacia_nome')  
                    ->get(),
                'creche' => MovCreche::where('associado', $user->id)->get(),
                'creche_associado' => MovCrecheAssociado::where('associado', $user->id)->get(),
            ];
            
            
        } elseif ($user->role == 3) {
            $lancamentos = [
                'farmacia' => MovFarmacia::all(),
                'creche' => MovCreche::all(),
                'creche_associado' => MovCrecheAssociado::all(),
            ];
        } else {
            return response()->json(['error' => 'Permissão negada'], 403);
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role
            ],
            'lancamentos' => $lancamentos
        ]);
    }


    
    private function checkDuplicatedInformation($usuario)
    {
        if (isset($usuario['email'])) {

            $email_in_use = User::where('email', $usuario['email'])->count();

            if ($email_in_use) {
                $message = '';

                if($email_in_use) {
                    $message = $message . "O email '{$usuario['email']}' já está em uso. ";
                }

                return response()->json([
                    'title'=> 'Falha na autenticação!',
                    'message'=>$message,

                ],401);
            }
        }

        return null;
    }

    public function login(Request $request){
        $request->validate([
            'cpf' => 'required|digits:11',
            'password' => 'required|string|min:5'
        ]);

        $cpf = preg_replace('/\D/', '', $request->cpf);

        $user = User::where('cpf', $cpf)->first();

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'CPF ou senha incorretos.'], 401);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role
            ]
        ]);
    }    

    public function index() {

        if (!JWTAuth::user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return response()->json(User::all());
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function profile() {

        if (!JWTAuth::user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return response()->json(JWTAuth::user());
    }

    public function logout() {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return response()->json($user);
    }

    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(['message' => 'Usuario excluído com sucesso']);
    }    
}

