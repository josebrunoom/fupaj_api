<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->char('cnpj', 14)->nullable();
            $table->string('empresa', 50)->nullable();
            $table->string('endereco', 50)->nullable();
            $table->string('numero', 50)->nullable();
            $table->string('complemento', 50)->nullable();
            $table->string('bairro', 50)->nullable();
            $table->string('cidade', 50)->nullable();
            $table->string('cep', 50)->nullable();
            $table->string('estado', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->dateTime('data_arquivo')->nullable();
            $table->string('nome_arquivo', 50)->nullable();
            $table->unsignedBigInteger('lote')->nullable();
            $table->string('banco', 50)->nullable();
            $table->string('agencia', 50)->nullable();
            $table->string('conta_corrente', 50)->nullable();
            $table->string('digito', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
