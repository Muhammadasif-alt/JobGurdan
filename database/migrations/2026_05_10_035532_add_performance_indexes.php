<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Indexes to create, keyed by table.
     *
     * @var array<string, array<string, list<string>>>
     */
    private array $indexes = [
        'jobs' => [
            'jobs_created_at_index' => ['created_at'],
            'jobs_status_index' => ['status'],
            'jobs_status_created_at_index' => ['status', 'created_at'],
            'jobs_position_index' => ['position'],
        ],
        'contact_messages' => [
            'contact_messages_status_index' => ['status'],
            'contact_messages_created_at_index' => ['created_at'],
        ],
        'users' => [
            'users_role_index' => ['role'],
        ],
        'blogs' => [
            'blogs_status_index' => ['status'],
            'blogs_published_at_index' => ['published_at'],
        ],
    ];

    public function up(): void
    {
        foreach ($this->indexes as $table => $indexes) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach ($indexes as $name => $columns) {
                if (! Schema::hasColumns($table, $columns) || Schema::hasIndex($table, $name)) {
                    continue;
                }

                Schema::table($table, function (Blueprint $blueprint) use ($columns, $name): void {
                    $blueprint->index($columns, $name);
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->indexes as $table => $indexes) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            foreach (array_keys($indexes) as $name) {
                if (! Schema::hasIndex($table, $name)) {
                    continue;
                }

                Schema::table($table, function (Blueprint $blueprint) use ($name): void {
                    $blueprint->dropIndex($name);
                });
            }
        }
    }
};
