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
        Schema::table('dual_project_reports', function (Blueprint $table) {
            $table->dropColumn(['number_men', 'number_women']);


            $table->boolean('is_concluded')->default(false); 
            $table->boolean('is_hired')->default(false);  
            $table->integer('qualification')->nullable();         
            $table->enum('advisor', ['interno', 'externo'])->default('interno');        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dual_project_reports', function (Blueprint $table) {
                        
            $table->integer('number_men')->nullable();
            $table->integer('number_women')->nullable();

            $table->dropColumn(['is_concluded', 'is_hired', 'qualification', 'advisor']);
        });
    }
};
