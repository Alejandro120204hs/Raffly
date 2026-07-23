<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rifas', function (Blueprint $table) {
            $table->id();
            // Definición de las columnas de la tabla 'rifas'
            $table->string('nombre');
            $table->string('tipo');
            $table->string('premio');
            $table->integer('cifras');
            $table->integer('precio');
            $table->string('loteria');
            $table->string('juega');
            $table->date('fecha');
            $table->string('estado')->default('activa');
            $table->string('resultado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rifas');
    }
};
