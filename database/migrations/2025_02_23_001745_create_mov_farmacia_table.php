<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovFarmaciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mov_farmacia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('autonumero')->nullable(); // Auto-increment
            $table->unsignedBigInteger('numnota')->nullable();
            $table->unsignedBigInteger('farmacia')->nullable();
            $table->unsignedBigInteger('associado')->nullable();
            $table->decimal('valornota', 18, 2)->nullable();
            $table->decimal('valorfundacao', 18, 2)->nullable();
            $table->decimal('valorassociado', 18, 2)->nullable();
            $table->dateTime('lancamento')->nullable();
            $table->dateTime('emissao')->nullable();
            $table->char('receita_sn', 3)->nullable();
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
        Schema::dropIfExists('mov_farmacia');
    }
}
