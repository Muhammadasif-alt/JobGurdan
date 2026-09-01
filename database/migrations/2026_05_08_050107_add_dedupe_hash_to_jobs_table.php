<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('jobs', 'dedupe_hash')) {
                $table->string('dedupe_hash', 64)->nullable()->after('application_url')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            if (Schema::hasColumn('jobs', 'dedupe_hash')) {
                $table->dropIndex(['dedupe_hash']);
                $table->dropColumn('dedupe_hash');
            }
        });
    }
};
