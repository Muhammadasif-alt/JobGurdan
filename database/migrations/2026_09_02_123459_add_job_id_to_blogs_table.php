<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Links a "job spotlight" post to the vacancy it writes about, so the post can
 * carry JobPosting markup for that specific opening.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('blogs', 'job_id')) {
            return;
        }

        Schema::table('blogs', function (Blueprint $table) {
            $table->foreignId('job_id')->nullable()->after('author_id')->constrained('jobs')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('blogs', 'job_id')) {
            return;
        }

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('job_id');
        });
    }
};
