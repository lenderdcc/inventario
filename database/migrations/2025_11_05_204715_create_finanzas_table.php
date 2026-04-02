<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up(): void
{
    Schema::create('finanzas', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('producto_id');
        $table->unsignedBigInteger('usuario_id');
        $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia', 'otro']);
        $table->enum('estado_pago', ['pendiente', 'completado', 'fallido']);
        $table->string('referencia_pago', 100)->nullable();
        $table->text('descripcion')->nullable();
        $table->timestamp('fecha_procesado')->useCurrent();

        $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
        $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finanzas');
    }
};
