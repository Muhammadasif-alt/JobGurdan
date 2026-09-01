<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->boolean('is_spam')->default(false)->after('status');
            $table->unsignedTinyInteger('spam_score')->nullable()->after('is_spam');
            $table->string('spam_reason', 255)->nullable()->after('spam_score');
            $table->index('is_spam');
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropIndex(['is_spam']);
            $table->dropColumn(['is_spam', 'spam_score', 'spam_reason']);
        });
    }
};
