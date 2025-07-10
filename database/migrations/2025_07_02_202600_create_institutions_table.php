<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('street');
            $table->string('external_number');
            $table->string('internal_number')->nullable();
            $table->string('neighborhood');
            $table->string('postal_code');
            $table->foreignId('id_state')->constrained('states')->onDelete('cascade');
            $table->foreignId('id_municipality')->constrained('municipalities')->onDelete('cascade');
            $table->string('country')->default('Mexico');
            $table->string('city')->nullable();
            $table->string('google_maps')->nullable();
            $table->integer('type');
            $table->foreignId('id_subsystem')->constrained('subsystems')->onDelete('cascade');
            $table->foreignId('id_academic_period')->constrained('academic_periods')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
