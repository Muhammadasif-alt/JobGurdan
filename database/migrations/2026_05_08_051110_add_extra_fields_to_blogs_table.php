<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            if (!Schema::hasColumn('blogs', 'author_name')) {
                $table->string('author_name', 100)->nullable()->after('author_id');
            }
            if (!Schema::hasColumn('blogs', 'tags')) {
                $table->string('tags', 500)->nullable()->after('content')
                      ->comment('comma-separated tags');
            }
            if (!Schema::hasColumn('blogs', 'gallery_images')) {
                $table->json('gallery_images')->nullable()->after('featured_image');
            }
            if (!Schema::hasColumn('blogs', 'meta_title')) {
                $table->string('meta_title', 160)->nullable()->after('gallery_images');
            }
            if (!Schema::hasColumn('blogs', 'meta_description')) {
                $table->string('meta_description', 300)->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('blogs', 'reading_time')) {
                $table->unsignedSmallInteger('reading_time')->nullable()->after('meta_description')
                      ->comment('estimated minutes');
            }
            if (!Schema::hasColumn('blogs', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            foreach (['author_name','tags','gallery_images','meta_title','meta_description','reading_time','is_featured'] as $col) {
                if (Schema::hasColumn('blogs', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
