<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rifa_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nombre_participante')->nullable();
            $table->integer('numero');
            $table->enum('estado', ['reservado', 'confirmado'])->default('confirmado');
            $table->timestamps();

            $table->unique(['rifa_id', 'numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participaciones');
    }
};
