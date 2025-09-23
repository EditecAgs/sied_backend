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
         Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['id_dual_project']); 
            $table->dropColumn('id_dual_project');   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('id_dual_project')->nullable()->after('id_specialty');
            $table->foreign('id_dual_project')->references('id')->on('dual_projects')->onDelete('restrict');
        });
    }
};
