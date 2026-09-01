<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            // Comma-separated string of extracted skills/keywords (kept as TEXT
            // for portable LIKE-based searches). Index supports prefix matches
            // via FULLTEXT/LIKE for fast keyword filtering.
            $table->text('seo_keywords')->nullable()->after('description');
            $table->string('meta_description', 320)->nullable()->after('seo_keywords');
        });

        // Add FULLTEXT index on the combined fields so keyword search is fast.
        // Only attempt on MySQL — SQLite/other drivers will silently skip.
        try {
            \Illuminate\Support\Facades\DB::statement(
                'ALTER TABLE jobs ADD FULLTEXT INDEX jobs_keywords_fulltext (position, description, seo_keywords)'
            );
        } catch (\Throwable $e) {
            // Index may already exist or driver doesn't support FULLTEXT — non-fatal
        }
    }

    public function down(): void
    {
        try {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE jobs DROP INDEX jobs_keywords_fulltext');
        } catch (\Throwable $e) {
        }

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['seo_keywords', 'meta_description']);
        });
    }
};
