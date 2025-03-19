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
    protected $description = 'Atualiza o campo email e senha dos usuários usando o CPF, ignorando duplicatas';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = DB::table('users')->whereNotNull('CPF')->get();
        $processedCpfs = []; // Lista para armazenar CPFs já processados

        foreach ($users as $user) {
            $cpf = preg_replace('/\D/', '', $user->CPF); // Remove caracteres não numéricos

            if (strlen($cpf) === 11) {
                // Se o CPF já foi processado, pula esse usuário
                if (in_array($cpf, $processedCpfs)) {
                    $this->warn("CPF {$cpf} já foi atualizado. Pulando usuário ID {$user->id}");
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
            } else {
                $this->warn("CPF inválido para o usuário ID {$user->id}");
            }
        }

        return 0;
    }
}
