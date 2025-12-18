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
            $table->integer('economic_benefit')->nullable();
            $table->string('economic_benefit_note')->nullable();
            $table->integer('time_benefit')->nullable();
            $table->string('time_benefit_note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dual_project_reports', function (Blueprint $table) {
            Schema::table('dual_project_reports', function (Blueprint $table) {
                $table->dropColumn([
                    'economic_benefit',
                    'economic_benefit_note',
                    'time_benefit',
                    'time_benefit_note'
                ]);
            });
        });
    }
};
