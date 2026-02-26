<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dual_project_students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_student')
                ->constrained('students')
                ->restrictOnDelete();

            $table->foreignUuid('id_dual_project')
                ->constrained('dual_projects')
                ->restrictOnDelete();


            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dual_project_students');
    }
};
