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
            $table->id();

            $table->foreignId('id_dual_project_report')
                ->constrained('dual_project_reports')
                ->cascadeOnDelete();

            $table->foreignId('id_benefit_type')
                ->constrained('benefit_types')
                ->cascadeOnDelete();

            $table->decimal('quantity', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
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
