<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One row per scholarship, written up as a guide that links out to the
 * provider's official page to apply.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('provider');
            $table->string('country', 100);
            $table->string('city', 100)->nullable();
            $table->string('study_level', 150);
            $table->string('funding_type', 60)->nullable();
            $table->string('award_value', 150)->nullable();
            $table->date('deadline')->nullable();
            $table->string('deadline_note', 150)->nullable();
            $table->string('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('apply_url', 500);
            $table->string('meta_title', 160)->nullable();
            $table->string('meta_description', 300)->nullable();
            $table->string('status', 20)->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
