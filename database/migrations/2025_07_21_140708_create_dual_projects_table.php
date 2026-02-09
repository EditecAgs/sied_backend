<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dual_projects', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->boolean('has_report');
            $table->integer('number_men');
            $table->integer('number_women');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dual_projects');
    }
};
