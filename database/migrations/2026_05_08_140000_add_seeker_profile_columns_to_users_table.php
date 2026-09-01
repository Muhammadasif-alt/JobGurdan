<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds the columns a job seeker needs to build out a useful profile —
     * headline, bio, resume upload path, skills, location and preferences.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'headline'))         $table->string('headline', 191)->nullable()->after('phone');
            if (! Schema::hasColumn('users', 'bio'))              $table->text('bio')->nullable()->after('headline');
            if (! Schema::hasColumn('users', 'skills'))           $table->text('skills')->nullable()->after('bio');
            if (! Schema::hasColumn('users', 'cv_path'))          $table->string('cv_path', 255)->nullable()->after('skills');
            if (! Schema::hasColumn('users', 'preferred_city'))   $table->string('preferred_city', 120)->nullable()->after('cv_path');
            if (! Schema::hasColumn('users', 'experience_years')) $table->unsignedSmallInteger('experience_years')->nullable()->after('preferred_city');
            if (! Schema::hasColumn('users', 'open_to'))          $table->string('open_to', 60)->nullable()->after('experience_years');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['headline', 'bio', 'skills', 'cv_path', 'preferred_city', 'experience_years', 'open_to'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
