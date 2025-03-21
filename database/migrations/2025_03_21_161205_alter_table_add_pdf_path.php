<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mov_farmacia', function (Blueprint $table) {
            $table->string('pdf_path')->nullable()->after('observacao_delete');
        });
    }

    public function down(): void
    {
        Schema::table('mov_farmacia', function (Blueprint $table) {
            $table->dropColumn('pdf_path');
        });
    }
};
