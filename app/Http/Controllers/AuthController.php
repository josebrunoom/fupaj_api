<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $dependentsQuery = User::whereIn('users.PARENTESCO', $dependents)
                ->whereBetween('users.NASCIMENTO', [$twentyFourStart, $twentyFourEnd])
                ->orWhereBetween('users.NASCIMENTO', [$twentyOneStart, $twentyOneEnd])
                ->join('users as parent', 'parent.codigo', '=', 'users.codigo')
                ->select('users.id', 'users.NOME', 'users.CPF', 'users.NASCIMENTO', 'users.PARENTESCO', 'parent.NOME as parent_name');
    
            // Agregados que completam 40 anos nos próximos 3 meses
            $aggregatesQuery = User::whereIn('users.PARENTESCO', $aggregates)
                ->whereBetween('users.NASCIMENTO', [$fortyStart, $fortyEnd])
                ->join('users as parent', 'parent.codigo', '=', 'users.codigo')
                ->select('users.id', 'users.NOME', 'users.CPF', 'users.NASCIMENTO', 'users.PARENTESCO', 'parent.NOME as parent_name');
    
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
        $formatDate = function ($date) {
            return ($date && $date !== '0000-00-00 00:00:00') 
                ? Carbon::parse($date)->format('d/m/Y') 
                : null;
        };

        $users = User::where('PARENTESCO', '00-TITULAR')
        ->orderBy('NOME', 'asc')
        ->get()->map(function ($user) use ($formatDate) {
            $user->NASCIMENTO = $formatDate($user->NASCIMENTO);
            $user->DATAHORA = $formatDate($user->DATAHORA);
            return $user;
        });
        return response()->json(['associados' => $users]);
    }

    public function getDependentes(){
        $dependents = [
            '10-FILHOS', '11-FILHOS', '12-FILHOS', '13-FILHOS', '30-FILHAS',
            '31-FILHAS', '32-FILHAS', '34-FILHAS', '35-FILHAS', 
            'ENTEADO', 'ENTEADA', 'ENTADO (A)', 'ENTEADO (A)', 
            '60-OUTROS DEPENDENTES'
        ];

        $formatDate = function ($date) {
            return ($date && $date !== '0000-00-00 00:00:00') 
                ? Carbon::parse($date)->format('d/m/Y') 
                : null;
        };

        $users = User::whereIn('PARENTESCO', $dependents)
        ->orderBy('NOME', 'asc')
            ->get()->map(function ($user) use ($formatDate) {
            $user->NASCIMENTO = $formatDate($user->NASCIMENTO);
            $user->DATAHORA = $formatDate($user->DATAHORA);
            $user->PARENTESCO_FORMATADO = preg_replace('/^\d+-/', '', $user->PARENTESCO);
            return $user;
        });

        return response()->json(['dependentes' => $users]);
    }

    public function getDependentesById($id){
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado'], 404);
        }
        return response()->json($user);
    }

    public function getAgregados() {
        $aggregates = ['90-AGREGADOS'];

        $formatDate = function ($date) {
            return ($date && $date !== '0000-00-00 00:00:00') 
                ? Carbon::parse($date)->format('d/m/Y') 
                : null;
        };

        $users = User::whereIn('PARENTESCO', $aggregates)
        ->orderBy('NOME', 'asc')
            ->get()->map(function ($user) use ($formatDate) {
            $user->NASCIMENTO = $formatDate($user->NASCIMENTO);
            $user->DATAHORA = $formatDate($user->DATAHORA);
            $user->PARENTESCO_FORMATADO = preg_replace('/^\d+-/', '', $user->PARENTESCO);
            return $user;
        });

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
                    ->join('users', 'mov_farmacia.associado', '=', 'users.id')
                    ->select('mov_farmacia.*', 'farmacias.nome as farmacia_nome', 'users.NOME as associado_nome') 
                    ->get()->map(function($mov) {
                        $mov->lancamento = Carbon::parse($mov->lancamento)->format('d/m/Y H:i');
                        $mov->emissao = Carbon::parse($mov->emissao)->format('d/m/Y H:i');
                        $mov->datahora = Carbon::parse($mov->datahora)->format('d/m/Y H:i');
                        return $mov;
                    }),
                'creche' => DB::table('mov_creches')
                    ->join('users', 'mov_creches.associado', '=', $user->id)
                    ->select('mov_creches.*', 'users.NOME as associado_nome') 
                    ->get()->map(function($creche) {
                        $creche->lancamento = Carbon::parse($creche->lancamento)->format('d/m/Y H:i');
                        $creche->pagamento = Carbon::parse($creche->pagamento)->format('d/m/Y H:i');
                        $creche->datahora = Carbon::parse($creche->datahora)->format('d/m/Y H:i');
                        return $creche;
                    }),
                'creche_associado' => MovCrecheAssociado::where('associado', $user->id)
                    ->get()->map(function($creche_associado) {
                        $creche_associado->lancamento = Carbon::parse($creche_associado->lancamento)->format('d/m/Y H:i');
                        $creche_associado->pagamento = Carbon::parse($creche_associado->pagamento)->format('d/m/Y H:i');
                        $creche_associado->datahora = Carbon::parse($creche_associado->datahora)->format('d/m/Y H:i');
                        $creche_associado->data_inicio = Carbon::parse($creche_associado->data_inicio)->format('d/m/Y H:i');
                        $creche_associado->data_termino = Carbon::parse($creche_associado->data_termino)->format('d/m/Y H:i');
                        return $creche_associado;
                    }),
            ];
        } 
        elseif ($user->role == 3) { //ADM retorna os dados de todos
            $lancamentos = [
                'farmacia' => MovFarmacia::join('farmacias', 'mov_farmacia.farmacia', '=', 'farmacias.codigo')
                    ->join('users', 'mov_farmacia.associado', '=', 'users.id')
                    ->select('mov_farmacia.*', 'farmacias.nome as farmacia_nome', 'users.NOME as associado_nome')  
                    ->get()->map(function($mov) {
                        $mov->lancamento = Carbon::parse($mov->lancamento)->format('d/m/Y H:i');
                        $mov->emissao = Carbon::parse($mov->emissao)->format('d/m/Y H:i');
                        $mov->datahora = Carbon::parse($mov->datahora)->format('d/m/Y H:i');
                        return $mov;
                    }),
                'creche' => MovCreche::join('users', 'mov_creches.associado', '=', 'users.id')
                ->select('mov_creches.*', 'users.NOME as associado_nome')
                ->get()->map(function($creche) {
                    $creche->lancamento = Carbon::parse($creche->lancamento)->format('d/m/Y H:i');
                    $creche->pagamento = Carbon::parse($creche->pagamento)->format('d/m/Y H:i');
                    $creche->datahora = Carbon::parse($creche->datahora)->format('d/m/Y H:i');
                    return $creche;
                }),
                'creche_associado' => MovCrecheAssociado::join('users', 'mov_creches_associados.associado', '=', 'users.id')
                ->select('mov_creches_associados.*', 'users.NOME as associado_nome')
                ->get()->map(function($creche_associado) {
                    $creche_associado->lancamento = Carbon::parse($creche_associado->lancamento)->format('d/m/Y H:i');
                    $creche_associado->pagamento = Carbon::parse($creche_associado->pagamento)->format('d/m/Y H:i');
                    $creche_associado->datahora = Carbon::parse($creche_associado->datahora)->format('d/m/Y H:i');
                    return $creche_associado;
                }),
            ];
        } else {
            return response()->json(['error' => 'Permissão negada'], 403);
        }
    
        return response()->json([
            'user' => [
                'id' => $user->id,
                'nome' => $user->NOME,
                'role' => $user->role
            ],
            'lancamentos' => $lancamentos
        ]);
    }
    

    public function createUserWithCategories(Request $request)
    {
        try {
            // Validação dos dados de entrada
            $request->validate([
                'user.NOME' => 'required|string|max:255',
                'user.CPF' => 'required|string|size:11|unique:users,CPF',
                'user.SEXO' => 'required|string|max:10',
                'user.SITUACAO' => 'required|string|max:20',
                'user.NASCIMENTO' => 'required|date',
                'user.CEP' => 'nullable|string|max:10',
                'user.ENDERECO' => 'nullable|string|max:255',
                'user.BAIRRO' => 'nullable|string|max:100',
                'user.CIDADE' => 'nullable|string|max:100',
                'user.ESTADO' => 'nullable|string|max:50',
                'user.TELEFONE' => 'nullable|string|max:20',
                'user.CELULAR' => 'nullable|string|max:20',
                'user.EMPRESA' => 'nullable|string|max:255',
                'user.BANCO' => 'nullable|string|max:255',
                'user.AGENCIA' => 'nullable|string|max:50',
                'user.CONTACORRENTE' => 'nullable|string|max:50',
                'user.CARTPROFISSIONAL' => 'nullable|string|max:50',
                'user.DOCTO_SUS' => 'nullable|string|max:50',
                'user.ESTADOCIVIL' => 'nullable|string|max:50',
                'user.FUNCAO' => 'nullable|string|max:255',
                'user.NOME_MAE' => 'nullable|string|max:255',
                'user.NOME_PAI' => 'nullable|string|max:255',
                'categorias' => 'array',
                'categorias.*' => 'exists:chq_categorias,id',
            ]);

            // Extraindo os dados do usuário do request
            $userData = $request->input('user');

            // Gerar email e senha com base no CPF
            $cpf = $userData['CPF'];
            $email = $cpf;
        

            DB::beginTransaction();

            // Criar o usuário
            $user = User::create([
                'name' => $userData['NOME'],
                'email' => $userData['CPF'],
                'password' => Hash::make($userData['password']),
                'role' => $userData['role'],
                'NOME' => $userData['NOME'],
                'SITUACAO' => $userData['SITUACAO'],
                'SEXO' => $userData['SEXO'],
                'NASCIMENTO' => $userData['NASCIMENTO'],
                'CEP' => $userData['CEP'],
                'ENDERECO' => $userData['ENDERECO'],
                'BAIRRO' => $userData['BAIRRO'],
                'CIDADE' => $userData['CIDADE'],
                'ESTADO' => $userData['ESTADO'],
                'TELEFONE' => $userData['TELEFONE'],
                'CELULAR' => $userData['CELULAR'],
                'EMPRESA' => $userData['EMPRESA'],
                'BANCO' => $userData['BANCO'],
                'AGENCIA' => $userData['AGENCIA'],
                'CONTACORRENTE' => $userData['CONTACORRENTE'],
                'CARTPROFISSIONAL' => $userData['CARTPROFISSIONAL'],
                'DOCTO_SUS' => $userData['DOCTO_SUS'],
                'ESTADOCIVIL' => $userData['ESTADOCIVIL'],
                'FUNCAO' => $userData['FUNCAO'],
                'NOME_MAE' => $userData['NOME_MAE'],
                'NOME_PAI' => $userData['NOME_PAI'],
                'PARENTESCO' => $userData['PARENTESCO'],
                'CPF' => $userData['CPF'],
            ]);

            // Save the generated user ID to CODIGO column if CODIGO is empty
            if (empty($userData['CODIGO'])) {
                $user->CODIGO = $user->id;
            } else {
                $user->CODIGO = $userData['CODIGO'];
            }
            $user->DATAHORA = Carbon::now();
            $user->save();

            $categorias = $request->input('categorias'); 
            foreach ($categorias as $categoriaId) {
                DB::table('chq_categorias_associado')->insert([
                    'associado' => $user->id, 
                    'categoria' => $categoriaId,
                ]);
            }
            
            $token = JWTAuth::fromUser($user);

            DB::commit();

            return response()->json([
                'user' => $user,
                'categorias' => $categorias,
                'token' => $token
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json([
                'errors' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage(),
                'error' => 'Não foi possível criar o usuário, tente novamente mais tarde',
            ], 422);
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
        try {
            Log::info('Login attempt', ['cpf' => $request->cpf]);
            
            // Validação básica dos campos
            $request->validate([
                'cpf' => 'required',
                'password' => 'required|string|min:5'
            ]);

            // Limpa o CPF de caracteres não numéricos
            $cpf = preg_replace('/\D/', '', $request->cpf);
            
            Log::info('Searching for user with CPF', ['cpf' => $cpf]);

            $user = User::where('cpf', $cpf)->first();

            if (!$user) {
                Log::warning('User not found', ['cpf' => $cpf]);
                return response()->json(['error' => 'Usuário não encontrado'], 404);
            }

            if (!Hash::check($request->password, $user->password)) {
                Log::warning('Invalid password for user', ['cpf' => $cpf]);
                return response()->json(['error' => 'CPF ou senha incorretos.'], 401);
            }

            $token = JWTAuth::fromUser($user);
            Log::info('Login successful', ['user_id' => $user->id]);

            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'nome' => $user->NOME,
                    'role' => $user->role
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Login error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Erro interno do servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }    

    public function recoverPassword(Request $request){
        $request->validate([
            'CPF' => 'required|digits:11',
            'NOME_MAE' => 'required|string'
        ]);

        $cpf = preg_replace('/\D/', '', $request->CPF);

        $user = User::where('CPF', $cpf)->where('NOME_MAE', $request->NOME_MAE)->first();

        if (!$user) {
            return response()->json(['error' => 'Dados inválidos. Verifique as informações fornecidas.'], 404);
        }

        // Gerar nova senha baseada nos 5 primeiros dígitos do CPF
        $novaSenha = substr($cpf, 0, 5);

        // Atualizar a senha no banco (criptografada)
        $user->PASSWORD = Hash::make($novaSenha);
        $user->save();

        return response()->json([
            'message' => 'Senha redefinida com sucesso.',
            'nova_senha' => $novaSenha
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

    public function showCompleto($id)
    {
        $user = User::findOrFail($id);
        $user->categorias = \DB::table('chq_categorias')
            ->leftJoin('chq_categorias_associado', function($join) use ($user) {
                $join->on('chq_categorias.id', '=', 'chq_categorias_associado.categoria')
                    ->where('chq_categorias_associado.associado', $user->id);
            })
            ->select(
                'chq_categorias.*',
                \DB::raw('CASE WHEN chq_categorias_associado.id IS NOT NULL THEN 1 ELSE 0 END as selected')
            )
            ->get();
        $user->dependentes = \DB::table('users')
            ->where('codigo', $user->id)
            ->where('parentesco', '!=', '00-TITULAR')
            ->get();
        return response()->json($user);
    }

    public function associadosCompletoAtivos()
    {
        $users = User::with(['categorias', 'dependentes'])
            ->where(function($query) {
                $query->where('SITUACAO', 'ATIVO')
                      ->orWhere('SITUACAO', 'APOSENTADO');
            })
            ->where('PARENTESCO', '00-TITULAR')
            ->get();

        return response()->json($users);
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

