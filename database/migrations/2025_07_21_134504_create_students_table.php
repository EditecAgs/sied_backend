<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('control_number');
            $table->string('name');
            $table->string('lastname');
            $table->enum('gender', ['Masculino', 'Femenino', 'Otro']);
            $table->integer('semester');
            $table->foreignId('id_institution')->constrained('institutions')->onDelete('restrict');
            $table->foreignId('id_career')->constrained('careers')->onDelete('restrict');
            $table->foreignId('id_specialty')->nullable()->constrained('specialties')->onDelete('restrict');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
