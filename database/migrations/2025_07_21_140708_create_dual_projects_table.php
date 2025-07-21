<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dual_projects', function (Blueprint $table) {
            $table->id();
            $table->boolean('has_report');
            $table->integer('number_men');
            $table->integer('number_women');
            $table->foreignId('id_dual_area')->constrained('dual_areas')->onDelete('restrict');
            $table->date('period_start');
            $table->date('period_end');
            $table->foreignId('status_document')->constrained('document_statuses')->onDelete('restrict');
            $table->foreignId('economic_support')->constrained('economic_supports')->onDelete('restrict');
            $table->double('amount')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dual_projects');
    }
};
