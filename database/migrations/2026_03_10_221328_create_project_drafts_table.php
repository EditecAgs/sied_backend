<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('project_drafts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignUuid('project_id')
                ->nullable()
                ->constrained('dual_projects')
                ->onDelete('cascade');

            $table->json('form_data');
            $table->boolean('reporta_modelo_dual')->default(false);
            $table->boolean('section1_expanded')->default(true);
            $table->boolean('section2_expanded')->default(false);
            $table->boolean('section3_expanded')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'project_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_drafts');
    }
};
