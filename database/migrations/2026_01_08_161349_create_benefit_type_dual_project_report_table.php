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
        Schema::create('benefit_type_dual_project_report', function (Blueprint $table) {
            $table->foreignUuid('id_dual_project_report');
            $table->foreignUuid('id_benefit_type');

            $table->uuid('id')->primary();

            $table->decimal('quantity', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_dual_project_report')
                  ->references('id')
                  ->on('dual_project_reports')
                  ->cascadeOnDelete();

            $table->foreign('id_benefit_type')
                  ->references('id')
                  ->on('benefit_types')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('benefit_type_dual_project_report');
    }
};
