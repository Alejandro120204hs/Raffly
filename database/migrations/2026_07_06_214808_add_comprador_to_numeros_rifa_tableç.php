<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('numeros_rifa', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('estado');
            $table->string('comprador_nombre')->nullable()->after('user_id');
            $table->string('comprador_apellido')->nullable()->after('comprador_nombre');
            $table->string('comprador_ubicacion')->nullable()->after('comprador_apellido');
            $table->string('comprador_celular')->nullable()->after('comprador_ubicacion');
        });
    }

    public function down(): void
    {
        Schema::table('numeros_rifa', function (Blueprint $table) {
            $table->dropColumn(['user_id','comprador_nombre','comprador_apellido','comprador_ubicacion','comprador_celular']);
        });
    }
};
