<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds a JSON column on users that holds the structured resume sections
     * (experience, education, certifications, languages, awards, raw_text)
     * extracted from a seeker's uploaded CV. Each upload overwrites it.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'resume_data')) {
                $table->json('resume_data')->nullable()->after('cv_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'resume_data')) {
                $table->dropColumn('resume_data');
            }
        });
    }
};
