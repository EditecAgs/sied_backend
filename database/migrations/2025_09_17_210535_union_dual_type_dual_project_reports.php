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
        Schema::table('dual_project_reports', function (Blueprint $table) {
         
            $table->foreignId('dual_type_id')
                  ->constrained('dual_types') 
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dual_project_reports', function (Blueprint $table) {
            $table->dropForeign(['dual_type_id']);
            $table->dropColumn('dual_type_id');
        });
    }
};
