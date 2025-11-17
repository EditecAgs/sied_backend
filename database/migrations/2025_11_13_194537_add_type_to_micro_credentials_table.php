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
        Schema::table('micro_credentials', function (Blueprint $table) {
            $table->enum('type', ['academic', 'no_academic'])
                  ->default('academic');
       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('micro_credentials', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
