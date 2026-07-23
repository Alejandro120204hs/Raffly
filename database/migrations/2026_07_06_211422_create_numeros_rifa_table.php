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
        Schema::create('numeros_rifa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rifa_id')->constrained('rifas')->onDelete('cascade');
            $table->string('numero');
            $table->enum('estado', ['disponible', 'pendiente', 'vendido'])->default('disponible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('numeros_rifa');
    }
};
