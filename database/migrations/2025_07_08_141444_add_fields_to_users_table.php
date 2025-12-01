<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('lastname')->after('name');
            $table->foreignUuid('id_institution')
                ->after('remember_token')
                ->constrained('institutions')
                ->restrictOnDelete();
            $table->integer('type')->after('id_institution');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_institution']);
            $table->dropColumn(['lastname', 'id_institution', 'type']);
        });
    }
};
