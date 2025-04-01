<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('medicos', function (Blueprint $table) {
            $table->id();
            $table->string('especialidade', 100)->nullable();
            $table->string('nome', 150)->nullable();
            $table->string('endereco', 200)->nullable();
            $table->string('bairro', 100)->nullable();
            $table->string('cidade', 100)->nullable();
            $table->string('uf', 2)->nullable();
            $table->string('cep', 9)->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('crm', 20)->nullable();
            $table->string('cpf', 14)->nullable();
            $table->string('usuario', 100)->nullable();
            $table->timestamp('datahora')->nullable()->useCurrent();
            $table->string('cnpj', 18)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('medicos');
    }
};
