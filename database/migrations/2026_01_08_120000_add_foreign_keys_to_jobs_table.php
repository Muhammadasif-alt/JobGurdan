<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('jobs')) {
            return;
        }

        Schema::table('jobs', function (Blueprint $table) {
            // Only add if not already present
            if (!Schema::hasColumn('jobs', 'category_id')) return;

            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null');

            $table->foreign('advertiser_id')
                  ->references('id')
                  ->on('advertisers')
                  ->onDelete('set null');

            $table->foreign('location_id')
                  ->references('id')
                  ->on('locations')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('jobs')) {
            return;
        }

        Schema::table('jobs', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrine = $sm->listTableDetails('jobs');

            // Drop constraints if exist
            if ($doctrine->hasForeignKey('jobs_category_id_foreign')) {
                $table->dropForeign('jobs_category_id_foreign');
            }
            if ($doctrine->hasForeignKey('jobs_advertiser_id_foreign')) {
                $table->dropForeign('jobs_advertiser_id_foreign');
            }
            if ($doctrine->hasForeignKey('jobs_location_id_foreign')) {
                $table->dropForeign('jobs_location_id_foreign');
            }
        });
    }
};