<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
            $dateLimit = $dateNow->copy()->addMonths(1);
    
            // Criando cópias para evitar alterar o mesmo objeto Carbon
            $twentyOneBirthday = $dateLimit->copy()->subYears(21)->toDateString();
            $twentyFourBirthday = $dateLimit->copy()->subYears(24)->toDateString();
            $fortyBirthday = $dateLimit->copy()->subYears(40)->toDateString();
    
            // Parentescos considerados dependentes
            $dependents = [
                '10-FILHOS', '11-FILHOS', '12-FILHOS', '13-FILHOS', '30-FILHAS',
                '31-FILHAS', '32-FILHAS', '34-FILHAS', '35-FILHAS', 
                'ENTEADO', 'ENTEADA', 'ENTADO (A)', 'ENTEADO (A)', 
                '60-OUTROS DEPENDENTES'
            ];
    
            // Parentesco considerado agregado
            $aggregates = ['90-AGREGADOS'];
    
            $dependentsQuery = User::whereIn('PARENTESCO', $dependents)
                ->whereBetween('NASCIMENTO', [$twentyFourBirthday . ' 00:00:00', $twentyFourBirthday . ' 23:59:59'])
                ->orWhereBetween('NASCIMENTO', [$twentyOneBirthday . ' 00:00:00', $twentyOneBirthday . ' 23:59:59']);

            $aggregatesQuery = User::whereIn('PARENTESCO', $aggregates)
                ->whereBetween('NASCIMENTO', [$fortyBirthday . ' 00:00:00', $fortyBirthday . ' 23:59:59']);

            // Une as consultas
            $users = $dependentsQuery->union($aggregatesQuery)->get();
    
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar usuários', 'message' => $e->getMessage()], 500);
        }
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

