<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('street');
            $table->string('external_number');
            $table->string('internal_number')->nullable();
            $table->string('neighborhood');
            $table->string('postal_code');
            $table->foreignUuid('id_state')
                ->constrained('states')
                ->cascadeOnDelete();
            $table->foreignUuid('id_municipality')
                ->constrained('municipalities')
                ->cascadeOnDelete();
            $table->string('country')->default('Mexico');
            $table->string('city')->nullable();
            $table->string('google_maps')->nullable();
            $table->integer('type');
            $table->foreignUuid('id_subsystem')
            ->constrained('subsystems')
            ->cascadeOnDelete();
            $table->foreignUuid('id_academic_period')
                ->constrained('academic_periods')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
