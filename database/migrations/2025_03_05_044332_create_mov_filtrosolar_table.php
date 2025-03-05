<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovFiltrosolarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mov_filtrosolar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('associado');
            $table->unsignedBigInteger('farmacia')->nullable();
            $table->unsignedBigInteger('numnota')->nullable();
            $table->dateTime('dataemissao')->nullable();
            $table->dateTime('lancamento')->nullable();
            $table->decimal('valorfiltro', 10, 2)->nullable();
            $table->decimal('valorfundacao', 10, 2)->nullable();
            $table->decimal('valorassociado', 10, 2)->nullable();
            $table->string('usuario', 10)->nullable();
            $table->dateTime('datahora')->nullable();
            $table->char('cancelamento', 3)->nullable();
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
        Schema::dropIfExists('mov_filtrosolar');
    }
}
