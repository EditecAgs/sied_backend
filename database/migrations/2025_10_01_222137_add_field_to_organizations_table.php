<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {

            $table->foreignUuid('id_cluster_local')
                  ->nullable()
                  ->after('id_cluster')
                  ->constrained('clusters')
                  ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign(['id_cluster_local']);
            $table->dropColumn('id_cluster_local');
        });
    }
};
