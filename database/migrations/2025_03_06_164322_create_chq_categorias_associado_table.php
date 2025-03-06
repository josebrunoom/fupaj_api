<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('chq_categorias_associado', function (Blueprint $table) {
            $table->id();
            $table->integer('categoria');
            $table->bigInteger('associado');
            $table->char('usuario', 10)->nullable();
            $table->dateTime('datahora')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chq_categorias_associado');
    }
};
