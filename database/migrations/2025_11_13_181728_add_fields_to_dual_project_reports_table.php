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
            $table->string('internal_advisor_name')->nullable();
            $table->integer('internal_advisor_qualification')->nullable(); 
            $table->string('external_advisor_name')->nullable();
            $table->integer('external_advisor_qualification')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dual_project_reports', function (Blueprint $table) {
                $table->dropColumn([
                'internal_advisor_name', 
                'internal_advisor_qualification', 
                'external_advisor_name', 
                'external_advisor_qualification'
            ]);
        });
    }
};
