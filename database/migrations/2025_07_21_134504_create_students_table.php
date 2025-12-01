<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('control_number');
            $table->string('name');
            $table->string('lastname');
            $table->enum('gender', ['Masculino', 'Femenino', 'Otro']);
            $table->integer('semester');
            $table->foreignUuid('id_institution')->constrained('institutions')->restrictOnDelete();
            $table->foreignUuid('id_career')->constrained('careers')->restrictOnDelete();
            $table->foreignUuid('id_specialty')->nullable()->constrained('specialties')->restrictOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
