<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the non-unique index first (added in earlier migration)
        Schema::table('jobs', function (Blueprint $table) {
            try { $table->dropIndex(['dedupe_hash']); } catch (\Throwable $e) { /* index may not exist */ }
        });

        // Add unique index. NULL values are allowed multiple times in MySQL unique indexes,
        // so jobs without a position (hash = null) won't conflict.
        Schema::table('jobs', function (Blueprint $table) {
            $table->unique('dedupe_hash', 'jobs_dedupe_hash_unique');
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            try { $table->dropUnique('jobs_dedupe_hash_unique'); } catch (\Throwable $e) {}
            $table->index('dedupe_hash');
        });
    }
};
