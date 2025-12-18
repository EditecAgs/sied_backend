<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('organizations', 'scope')) {
            DB::table('organizations')
                ->where('scope', 'Nacional')
                ->update(['scope' => 'Federal']);

            Schema::table('organizations', function (Blueprint $table) {
                $table->enum('scope', [
                    'Municipal',
                    'Federal',
                    'Estatal',
                    'Internacional',
                ])->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('organizations', 'scope')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->enum('scope', [
                    'Municipal',
                    'Nacional',
                    'Estatal',
                    'Internacional',
                ])->change();
            });

            DB::table('organizations')
                ->where('scope', 'Federal')
                ->update(['scope' => 'Nacional']);
        }
    }
};
