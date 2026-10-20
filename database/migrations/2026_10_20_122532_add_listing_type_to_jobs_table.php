<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * "vacancy" is one real opening. "resource" is a link to an official portal,
     * a guide or a job board search, which must not be counted or shown as a job.
     * Existing rows whose company is an "(Aggregated)" placeholder become
     * resources; nothing is deleted.
     */
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('listing_type', 20)->default('vacancy')->after('status')->index();
        });

        DB::table('jobs')
            ->whereIn('advertiser_id', DB::table('advertisers')->where('name', 'like', '%(Aggregated)%')->select('id'))
            ->update(['listing_type' => 'resource']);
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropIndex(['listing_type']);
            $table->dropColumn('listing_type');
        });
    }
};
