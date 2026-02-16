<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('id_type')->constrained('types')->onDelete('restrict');
            $table->foreignId('id_sector')->constrained('sectors')->onDelete('restrict');
            $table->enum('size', ['Micro (1 a 10 trabajadores)', 'Pequeña (11 a 50 trabajadores)', 'Mediana (51 a 100 trabajadores)', 'Grande (Más de 100 trabajadores)']);
            $table->foreignId('id_cluster')->constrained('clusters')->onDelete('restrict');
            $table->string('street');
            $table->string('external_number');
            $table->string('internal_number')->nullable();
            $table->string('neighborhood');
            $table->string('postal_code');
            $table->foreignId('id_state')->constrained('states')->onDelete('restrict');
            $table->foreignId('id_municipality')->constrained('municipalities')->onDelete('restrict');
            $table->string('country')->default('México');
            $table->string('city')->nullable();
            $table->string('google_maps')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
