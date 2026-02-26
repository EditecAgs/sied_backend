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
        Schema::create('dual_project_report_certification', function (Blueprint $table) {
            $table->foreignUuid('id_certification');
            $table->foreignUuid('id_dual_project_report');

            
            $table->uuid('id')->primary();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_certification')
                  ->references('id')
                  ->on('certifications')
                  ->onDelete('restrict');

            $table->foreign('id_dual_project_report')
                  ->references('id')
                  ->on('dual_project_reports')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dual_project_report_certification');
    }
};
