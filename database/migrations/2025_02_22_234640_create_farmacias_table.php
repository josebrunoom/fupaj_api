<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmaciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmacias', function (Blueprint $table) {
            $table->id('codigo'); // Auto-increment
            $table->string('cnpj', 30)->nullable();
            $table->string('nome', 50)->nullable();
            $table->string('endereco', 50)->nullable();
            $table->string('bairro', 21)->nullable();
            $table->string('cidade', 21)->nullable();
            $table->string('uf', 3)->nullable();
            $table->string('cep', 15)->nullable();
            $table->string('telefone', 15)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('inscricao_estadual', 20)->nullable();
            $table->char('usuario', 10)->nullable();
            $table->dateTime('data_hora')->nullable();
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
        Schema::dropIfExists('farmacias');
    }
}
