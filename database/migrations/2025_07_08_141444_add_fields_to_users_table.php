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
            $table->unsignedBigInteger('id_institution')->after('remember_token');
            $table->integer('type')->after('id_institution');
            $table->foreign('id_institution')->references('id')->on('institutions')->onDelete('restrict');
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
