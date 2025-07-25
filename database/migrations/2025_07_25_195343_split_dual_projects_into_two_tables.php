<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dual_projects', function (Blueprint $table) {
            $table->foreignId('id_institution')->nullable()->constrained('institutions')->onDelete('restrict')->after('has_report');
        });

        Schema::create('dual_project_reports', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('dual_project_id')->constrained('dual_projects')->onDelete('cascade');
            $table->integer('number_men');
            $table->integer('number_women');
            $table->foreignId('id_dual_area')->constrained('dual_areas')->onDelete('restrict');
            $table->date('period_start');
            $table->date('period_end');
            $table->foreignId('status_document')->constrained('document_statuses')->onDelete('restrict');
            $table->foreignId('economic_support')->constrained('economic_supports')->onDelete('restrict');
            $table->double('amount')->default(0);
            $table->timestamps();
        });

        $projects = DB::table('dual_projects')->where('has_report', true)->get();

        foreach ($projects as $project) {
            DB::table('dual_project_reports')->insert([
                'dual_project_id' => $project->id,
                'number_men' => $project->number_men,
                'number_women' => $project->number_women,
                'id_dual_area' => $project->id_dual_area,
                'period_start' => $project->period_start,
                'period_end' => $project->period_end,
                'status_document' => $project->status_document,
                'economic_support' => $project->economic_support,
                'amount' => $project->amount,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('dual_projects', function (Blueprint $table) {
            $table->dropForeign(['id_dual_area']);
            $table->dropForeign(['status_document']);
            $table->dropForeign(['economic_support']);
        });


        Schema::table('dual_projects', function (Blueprint $table) {
            $table->dropColumn([
                'number_men',
                'number_women',
                'id_dual_area',
                'period_start',
                'period_end',
                'status_document',
                'economic_support',
                'amount'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dual_projects', function (Blueprint $table) {
            $table->integer('number_men')->nullable();
            $table->integer('number_women')->nullable();
            $table->foreignId('id_dual_area')->nullable()->constrained('dual_areas')->onDelete('restrict');
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->foreignId('status_document')->nullable()->constrained('document_statuses')->onDelete('restrict');
            $table->foreignId('economic_support')->nullable()->constrained('economic_supports')->onDelete('restrict');
            $table->double('amount')->default(0)->nullable();
        });
        $reports = DB::table('dual_project_reports')->get();

        foreach ($reports as $report) {
            DB::table('dual_projects')
                ->where('id', $report->dual_project_id)
                ->update([
                    'number_men' => $report->number_men,
                    'number_women' => $report->number_women,
                    'id_dual_area' => $report->id_dual_area,
                    'period_start' => $report->period_start,
                    'period_end' => $report->period_end,
                    'status_document' => $report->status_document,
                    'economic_support' => $report->economic_support,
                    'amount' => $report->amount,
                    'has_report' => true,
                ]);
        }

        Schema::dropIfExists('dual_project_reports');
        Schema::table('dual_projects', function (Blueprint $table) {
            $table->dropForeign(['id_institution']);
            $table->dropColumn('id_institution');
        });
    }
};
