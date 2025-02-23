<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('CODIGO', 18, 0)->nullable();
            $table->string('SITUACAO', 20)->nullable();
            $table->string('SEXO', 10)->nullable();
            $table->string('NOME', 80)->nullable();
            $table->string('ENDERECO', 80)->nullable();
            $table->string('BAIRRO', 22)->nullable();
            $table->string('CIDADE', 20)->nullable();
            $table->string('ESTADO', 4)->nullable();
            $table->string('CEP', 20)->nullable();
            $table->string('ESTADOCIVIL', 21)->nullable();
            $table->string('TELEFONE', 20)->nullable();
            $table->string('CELULAR', 20)->nullable();
            $table->string('NACIONALIDADE', 50)->nullable();
            $table->string('IDENTIDADE', 25)->nullable();
            $table->string('CPF', 50)->nullable();
            $table->string('DATAADMISSAO_NU', 15)->nullable();
            $table->string('CARTPROFISSIONAL', 25)->nullable();
            $table->string('FUNCAO', 25)->nullable();
            $table->string('PLANOSAUDE', 3)->nullable();
            $table->string('PIS', 25)->nullable();
            $table->dateTime('DATADEMISSAO')->nullable();
            $table->char('USUARIO', 10)->nullable();
            $table->dateTime('DATAHORA')->nullable();
            $table->char('BANCO', 3)->nullable();
            $table->char('AGENCIA', 4)->nullable();
            $table->char('CONTACORRENTE', 15)->nullable();
            $table->char('DIGITOCONTA', 1)->nullable();
            $table->char('CONTAPOUPANCA', 3)->nullable();
            $table->dateTime('NASCIMENTO')->nullable();
            $table->char('EMPRESA', 20)->nullable();
            $table->dateTime('DATAADMISSAO')->nullable();
            $table->string('DOCTO_SUS', 50)->nullable();
            $table->string('NOME_PAI', 50)->nullable();
            $table->string('NOME_MAE', 50)->nullable();
            $table->dateTime('DATA_IDENTIDADE')->nullable();
            $table->char('ORGAO_IDENTIDADE', 20)->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::dropIfExists('users');
        });
    }
}
