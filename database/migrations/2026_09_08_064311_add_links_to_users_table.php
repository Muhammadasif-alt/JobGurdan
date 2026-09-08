<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Seekers are judged on work they can point at — a LinkedIn profile, an
     * Upwork or Fiverr storefront, a portfolio site. The single `website`
     * column only holds one of those, so store the set as a keyed map.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'links')) {
                $table->json('links')->nullable()->after('website');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'links')) {
                $table->dropColumn('links');
            }
        });
    }
};
