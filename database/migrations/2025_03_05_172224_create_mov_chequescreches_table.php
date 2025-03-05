<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovChequescrechesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mov_chequescreches', function (Blueprint $table) {
            $table->bigIncrements('AUTONUMERO');  // BIGINT AUTO_INCREMENT
            $table->integer('NUMCHEQUE')->nullable();
            $table->float('VALOR')->nullable();
            $table->integer('CATEGORIA')->nullable();
            $table->tinyInteger('IMPRESSO')->default(0);  // TINYINT para bit
            $table->tinyInteger('CANCELADO')->default(0);  // TINYINT para bit
            $table->dateTime('DATA')->nullable();
            $table->string('NOMINAL', 50)->nullable();
            $table->dateTime('DATACADASTRO')->nullable();
            $table->bigInteger('ASSOCIADO')->nullable();
            $table->string('OBSERVACAO', 200)->nullable();
            $table->char('USUARIO', 10)->nullable();
            $table->dateTime('DATAHORA')->nullable();
            $table->char('ENVIARITAU', 10)->nullable();
            $table->string('NOME_TXT_ITAU', 50)->nullable();
            $table->string('OBSERVACAO1', 200)->nullable();
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
        Schema::dropIfExists('mov_chequescreches');
    }
}
