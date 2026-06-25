<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rifas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('imagen')->nullable();
            $table->decimal('precio_numero', 10, 2);
            $table->integer('total_numeros')->default(100);
            $table->integer('numeros_vendidos')->default(0);
            $table->enum('estado', ['pendiente', 'activa', 'finalizada', 'cancelada'])->default('activa');
            $table->text('premio_descripcion')->nullable();
            $table->decimal('monto_premio', 14, 2)->nullable();
            $table->dateTime('fecha_sorteo');
            $table->integer('numero_ganador')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rifas');
    }
};
