<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['id_dual_project']);
            $table->dropColumn('id_dual_project');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignUuid('id_dual_project')
                  ->nullable()
                  ->after('id_specialty')
                  ->constrained('dual_projects')
                  ->restrictOnDelete();
        });
    }
};
