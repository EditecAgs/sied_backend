<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dual_project_reports', function (Blueprint $table) {
            $table->enum('max_qualification', [10, 100])->default(10);
        });
    }

    public function down(): void
    {
        Schema::table('dual_project_reports', function (Blueprint $table) {
            $table->dropColumn('max_qualification');
        });
    }
};
