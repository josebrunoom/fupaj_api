<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return response()->json(['token' => $token]);
    }

    public function index() {

        if (!JWTAuth::user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return response()->json(User::all());
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
}

