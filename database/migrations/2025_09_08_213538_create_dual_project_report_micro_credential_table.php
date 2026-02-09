<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dual_project_report_micro_credential', function (Blueprint $table) {

        $table->uuid('id')->primary();
        $table->foreignUuid('id_micro_credential')
            ->constrained('micro_credentials')
            ->restrictOnDelete()
            ->name('fk_dp_micro');

        $table->foreignUuid('id_dual_project_report')
            ->constrained('dual_project_reports')
            ->restrictOnDelete()
            ->name('fk_dp_report'); 



            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dual_project_report_micro_credential');
    }
};
