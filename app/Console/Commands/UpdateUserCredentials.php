<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateUserCredentials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-credentials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza o campo email e senha dos usuários usando o CPF, ignorando duplicatas, priorizando usuários com SITUACAO ATIVO';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Obtém todos os usuários com CPF válido e prioriza os que estão ATIVO
        $users = DB::table('users')
            ->whereNotNull('CPF')
            ->orderByRaw("CASE WHEN SITUACAO = 'ATIVO' THEN 1 ELSE 2 END")
            ->get();

        $processedCpfs = []; // Lista para armazenar CPFs já processados

        foreach ($users as $user) {
            $cpf = preg_replace('/\D/', '', $user->CPF); // Remove caracteres não numéricos

            // Garante que o CPF tenha pelo menos 5 dígitos, senão usa um fallback
            if (strlen($cpf) < 5) {
                $cpf = str_pad($cpf, 5, '0', STR_PAD_RIGHT); // Preenche com zeros à direita se necessário
                $this->warn("CPF {$user->CPF} ajustado para {$cpf} para o usuário ID {$user->id}");
            }

            // Se o CPF já foi processado, pula esse usuário
            if (in_array($cpf, $processedCpfs)) {
                $this->warn("CPF {$cpf} já foi atualizado. Pulando usuário ID {$user->id}");
                continue;
            }

            // Verifica se o email já existe na base de dados
            $emailExists = DB::table('users')->where('email', $cpf)->exists();
            if ($emailExists) {
                $this->warn("Email {$cpf} já existe. Pulando usuário ID {$user->id}");
                continue;
            }

            $email = $cpf; // CPF direto no campo email
            $password = substr($cpf, 0, 5); // Primeiros 5 dígitos do CPF
            $hashedPassword = Hash::make($password); // Criptografando a senha

            DB::table('users')->where('id', $user->id)->update([
                'email' => $email,
                'password' => $hashedPassword,
            ]);

            $this->info("Credenciais atualizadas para o usuário ID {$user->id}");

            // Adiciona o CPF à lista de CPFs já processados
            $processedCpfs[] = $cpf;
        }

        return 0;
    }
}
