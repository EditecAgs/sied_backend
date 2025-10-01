<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dual_project_students', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('id_student')->constrained('students')->onDelete('restrict');
            $table->foreignId('id_dual_project')->constrained('dual_projects')->onDelete('restrict');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dual_project_students');
    }
};
