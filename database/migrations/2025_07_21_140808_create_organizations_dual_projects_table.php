<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations_dual_projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_organization')->constrained('organizations')->restrictOnDelete();
            $table->foreignUuid('id_dual_project')->constrained('dual_projects')->restrictOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations_dual_projects');
    }
};
