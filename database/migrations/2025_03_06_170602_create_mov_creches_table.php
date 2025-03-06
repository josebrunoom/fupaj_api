<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('mov_creches', function (Blueprint $table) {
            $table->id(); 
            $table->bigInteger('associado');
            $table->dateTime('lancamento')->nullable();
            $table->dateTime('pagamento')->nullable();
            $table->decimal('valor', 19, 4)->nullable();
            $table->string('observacao', 500)->nullable();
            $table->char('tipo', 10)->nullable();
            $table->char('usuario', 10)->nullable();
            $table->dateTime('datahora')->nullable();
            $table->bigInteger('lancto_cheque')->nullable();
            $table->char('cancel_cheque', 10)->nullable();
            $table->bigInteger('controle')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mov_creches');
    }
};
