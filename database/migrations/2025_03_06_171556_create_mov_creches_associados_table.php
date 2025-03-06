<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('mov_creches_associados', function (Blueprint $table) {
            $table->id(); // BIGINT AUTO_INCREMENT PRIMARY KEY
            $table->bigInteger('associado');
            $table->char('tipo', 10)->nullable();
            $table->bigInteger('parcelas')->nullable();
            $table->dateTime('data_inicio')->nullable();
            $table->dateTime('data_termino')->nullable();
            $table->dateTime('lancamento')->nullable();
            $table->string('observacao', 500)->nullable();
            $table->char('status', 10)->nullable();
            $table->char('usuario', 10)->nullable();
            $table->dateTime('datahora')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mov_creches_associados');
    }
};