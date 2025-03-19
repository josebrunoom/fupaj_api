<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mov_creches_associados', function (Blueprint $table) {
            $table->softDeletes(); // Adiciona a coluna deleted_at para soft delete
            $table->string('observacao_delete', 500)->nullable()->after('deleted_at'); // Adiciona a coluna observacao_delete
        });
    }

    public function down(): void
    {
        Schema::table('mov_creches_associados', function (Blueprint $table) {
            $table->dropColumn('observacao_delete');
            $table->dropSoftDeletes();
        });
    }
};
