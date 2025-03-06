<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chq_categorias', function (Blueprint $table) {
            $table->id();
            $table->string('descricao', 200)->nullable();
            $table->char('gravarchq_cursos', 3)->nullable();
            $table->char('gravarchq_oticas', 3)->nullable();
            $table->char('usuario', 10)->nullable();
            $table->dateTime('datahora')->nullable();
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
        Schema::dropIfExists('chq_categorias');
    }
};
