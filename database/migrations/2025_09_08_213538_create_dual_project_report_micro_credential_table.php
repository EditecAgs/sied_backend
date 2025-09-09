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
        Schema::create('dual_project_report_micro_credential', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_micro_credential')->constrained('micro_credentials')->onDelete('restrict')->name('fk_micro_credential');
            $table->foreignId('id_dual_project_report')->constrained('dual_project_reports')->onDelete('restrict')->name('fk_dual_project_report');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dual_project_report_micro_credential');
    }
};
