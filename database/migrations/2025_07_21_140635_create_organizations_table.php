<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->foreignUuid('id_type')->constrained('types')->restrictOnDelete();
            $table->foreignUuid('id_sector')->constrained('sectors')->restrictOnDelete();
            $table->enum('size', ['Micro (1 a 10 trabajadores)', 'Pequeña (11 a 50 trabajadores)', 'Mediana (51 a 100 trabajadores)', 'Grande (Más de 100 trabajadores)']);
            $table->foreignUuid('id_cluster')->constrained('clusters')->restrictOnDelete();
            $table->string('street');
            $table->string('external_number');
            $table->string('internal_number')->nullable();
            $table->string('neighborhood');
            $table->string('postal_code');
            $table->foreignUuid('id_state')->constrained('states')->restrictOnDelete();
            $table->foreignUuid('id_municipality')->constrained('municipalities')->restrictOnDelete();
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
