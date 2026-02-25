<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dual_projects', function (Blueprint $table) {
            $table->foreignUuid('id_institution')
                ->nullable()
                ->after('has_report')
                ->constrained('institutions')
                ->restrictOnDelete();
        });

        Schema::create('dual_project_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name')->nullable();

            $table->foreignUuid('dual_project_id')
                ->constrained('dual_projects')
                ->cascadeOnDelete();

            $table->integer('number_men');
            $table->integer('number_women');

            $table->foreignUuid('id_dual_area')
                ->constrained('dual_areas')
                ->restrictOnDelete();

            $table->date('period_start');
            $table->date('period_end');

            $table->foreignUuid('status_document')
                ->constrained('document_statuses')
                ->restrictOnDelete();

            $table->foreignUuid('economic_support')
                ->constrained('economic_supports')
                ->restrictOnDelete();

            $table->double('amount')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dual_project_reports');

        Schema::table('dual_projects', function (Blueprint $table) {
            $table->dropForeign(['id_institution']);
            $table->dropColumn('id_institution');
        });
    }
};
