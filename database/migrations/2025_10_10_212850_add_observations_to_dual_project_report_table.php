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
            $table->text('period_observation')->nullable();
            $table->text('hired_observation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dual_project_reports', function (Blueprint $table) {
            $table->dropColumn('period_observation');
            $table->dropColumn('hired_observation');
        });
    }
};
