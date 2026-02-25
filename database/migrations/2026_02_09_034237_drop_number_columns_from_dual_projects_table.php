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
        Schema::table('dual_projects', function (Blueprint $table) {
            $table->dropColumn(['number_men', 'number_women']);
        });
    }

    public function down(): void
    {
        Schema::table('dual_projects', function (Blueprint $table) {
            $table->integer('number_men')->default(0);
            $table->integer('number_women')->default(0);
        });
    }

};
