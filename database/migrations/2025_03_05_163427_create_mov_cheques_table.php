<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mov_cheques', function (Blueprint $table) {
            $table->id();
            $table->integer('numcheque')->nullable();
            $table->double('valor')->nullable();
            $table->unsignedBigInteger('categoria')->nullable();
            $table->boolean('impresso')->default(0);
            $table->boolean('cancelado')->default(0);
            $table->dateTime('data')->nullable();
            $table->string('nominal', 50)->nullable();
            $table->dateTime('datacadastro')->nullable();
            $table->unsignedBigInteger('associado')->nullable();
            $table->string('observacao', 200)->nullable();
            $table->char('usuario', 10)->nullable();
            $table->dateTime('datahora')->nullable();
            $table->char('enviaritau', 10)->nullable();
            $table->string('nome_txt_itau', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mov_cheques');
    }
};

