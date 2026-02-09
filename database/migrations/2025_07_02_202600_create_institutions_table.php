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

            
            $table->uuid('id_state');
            $table->foreign('id_state')
                ->references('id')
                ->on('states')
                ->cascadeOnDelete();

           
            $table->uuid('id_municipality');
            $table->foreign('id_municipality')
                ->references('id')
                ->on('municipalities')
                ->cascadeOnDelete();

            $table->string('country')->default('Mexico');
            $table->string('city')->nullable();
            $table->string('google_maps')->nullable();
            $table->integer('type');

            
            $table->uuid('id_subsystem');
            $table->foreign('id_subsystem')
                ->references('id')
                ->on('subsystems')
                ->cascadeOnDelete();

            
            $table->uuid('id_academic_period');
            $table->foreign('id_academic_period')
                ->references('id')
                ->on('academic_periods')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
