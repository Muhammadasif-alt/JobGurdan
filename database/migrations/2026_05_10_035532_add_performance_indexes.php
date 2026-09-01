<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndexIfMissing('jobs', 'jobs_created_at_index', 'CREATE INDEX jobs_created_at_index ON jobs (created_at)');
        $this->addIndexIfMissing('jobs', 'jobs_status_index',     'CREATE INDEX jobs_status_index ON jobs (status)');
        $this->addIndexIfMissing('jobs', 'jobs_status_created_at_index', 'CREATE INDEX jobs_status_created_at_index ON jobs (status, created_at)');
        $this->addIndexIfMissing('jobs', 'jobs_position_index',   'CREATE INDEX jobs_position_index ON jobs (position)');

        $this->addIndexIfMissing('contact_messages', 'contact_messages_status_index', 'CREATE INDEX contact_messages_status_index ON contact_messages (status)');
        $this->addIndexIfMissing('contact_messages', 'contact_messages_created_at_index', 'CREATE INDEX contact_messages_created_at_index ON contact_messages (created_at)');

        if (Schema::hasColumn('users', 'role')) {
            $this->addIndexIfMissing('users', 'users_role_index', 'CREATE INDEX users_role_index ON users (role)');
        }

        if (Schema::hasTable('blogs')) {
            $this->addIndexIfMissing('blogs', 'blogs_status_index', 'CREATE INDEX blogs_status_index ON blogs (status)');
            $this->addIndexIfMissing('blogs', 'blogs_published_at_index', 'CREATE INDEX blogs_published_at_index ON blogs (published_at)');
        }
    }

    public function down(): void
    {
        $this->dropIndexIfExists('jobs', 'jobs_created_at_index');
        $this->dropIndexIfExists('jobs', 'jobs_status_index');
        $this->dropIndexIfExists('jobs', 'jobs_status_created_at_index');
        $this->dropIndexIfExists('jobs', 'jobs_position_index');
        $this->dropIndexIfExists('contact_messages', 'contact_messages_status_index');
        $this->dropIndexIfExists('contact_messages', 'contact_messages_created_at_index');
        $this->dropIndexIfExists('users', 'users_role_index');
        $this->dropIndexIfExists('blogs', 'blogs_status_index');
        $this->dropIndexIfExists('blogs', 'blogs_published_at_index');
    }

    private function addIndexIfMissing(string $table, string $indexName, string $sql): void
    {
        $exists = collect(DB::select("SHOW INDEX FROM `{$table}`"))->pluck('Key_name')->contains($indexName);
        if (! $exists) {
            DB::statement($sql);
        }
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        if (! Schema::hasTable($table)) return;
        $exists = collect(DB::select("SHOW INDEX FROM `{$table}`"))->pluck('Key_name')->contains($indexName);
        if ($exists) {
            DB::statement("DROP INDEX `{$indexName}` ON `{$table}`");
        }
    }
};
