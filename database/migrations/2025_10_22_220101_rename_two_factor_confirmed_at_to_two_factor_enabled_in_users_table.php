<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        if (Schema::hasColumn('users', 'two_factor_confirmed_at')) {
            // Agregar nueva columna
            $table->timestamp('two_factor_enabled')->nullable();

            // Copiar datos (si existían)
            DB::statement('UPDATE users SET two_factor_enabled = two_factor_confirmed_at');

            // Eliminar columna vieja
            $table->dropColumn('two_factor_confirmed_at');
        }
    });
}

   public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->timestamp('two_factor_confirmed_at')->nullable();
        DB::statement('UPDATE users SET two_factor_confirmed_at = two_factor_enabled');
        $table->dropColumn('two_factor_enabled');
    });
}

};
