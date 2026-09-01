<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds a website column for company users (and any future role that needs one).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'website')) {
                $table->string('website', 255)->nullable()->after('phone');
            }
            if (! Schema::hasColumn('users', 'address')) {
                $table->string('address', 255)->nullable()->after('website');
            }
            if (! Schema::hasColumn('users', 'company_size')) {
                $table->string('company_size', 50)->nullable()->after('address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['website', 'address', 'company_size'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
