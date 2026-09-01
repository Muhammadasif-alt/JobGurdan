<?php

namespace App\Console\Commands;

use App\Models\Job;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DedupeJobs extends Command
{
    protected $signature = 'jobs:dedupe {--dry-run : Show what would be deleted without actually deleting}';

    protected $description = 'Remove duplicate jobs (same position + employer + location). Keeps the oldest record.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $this->info($dryRun ? '[DRY RUN] No changes will be made.' : 'Starting deduplication…');

        // Step 1: Backfill dedupe_hash for any rows missing it
        $this->info('Step 1/3: Backfilling dedupe_hash for all jobs…');
        $totalJobs = Job::count();
        $bar = $this->output->createProgressBar($totalJobs);

        Job::query()
            ->select('id', 'position', 'advertiser_id', 'location_id')
            ->orderBy('id')
            ->chunkById(1000, function ($chunk) use ($bar) {
                foreach ($chunk as $job) {
                    $hash = self::makeHash($job->position, $job->advertiser_id, $job->location_id);
                    DB::table('jobs')->where('id', $job->id)->update(['dedupe_hash' => $hash]);
                    $bar->advance();
                }
            });
        $bar->finish();
        $this->newLine();

        // Step 2: Find duplicate hashes (groups with > 1 row), keep oldest (lowest id)
        $this->info('Step 2/3: Finding duplicates…');
        $duplicateGroups = DB::table('jobs')
            ->select('dedupe_hash', DB::raw('COUNT(*) as cnt'), DB::raw('MIN(id) as keep_id'))
            ->whereNotNull('dedupe_hash')
            ->groupBy('dedupe_hash')
            ->having('cnt', '>', 1)
            ->get();

        $totalToDelete = $duplicateGroups->sum(fn ($g) => $g->cnt - 1);

        $this->line("  Duplicate groups: <fg=yellow>{$duplicateGroups->count()}</>");
        $this->line("  Excess duplicate rows: <fg=red>{$totalToDelete}</>");

        if ($duplicateGroups->isEmpty()) {
            $this->info('No duplicates found. Database is clean.');
            return self::SUCCESS;
        }

        if ($dryRun) {
            $this->warn("[DRY RUN] Would delete {$totalToDelete} rows. Run without --dry-run to apply.");
            return self::SUCCESS;
        }

        // Step 3: Delete duplicates (keep oldest)
        $this->info('Step 3/3: Deleting duplicates (keeping oldest of each group)…');
        $deleted = 0;
        $bar = $this->output->createProgressBar($duplicateGroups->count());

        foreach ($duplicateGroups->chunk(500) as $chunk) {
            DB::transaction(function () use ($chunk, &$deleted, $bar) {
                foreach ($chunk as $group) {
                    $rowsDeleted = DB::table('jobs')
                        ->where('dedupe_hash', $group->dedupe_hash)
                        ->where('id', '!=', $group->keep_id)
                        ->delete();
                    $deleted += $rowsDeleted;
                    $bar->advance();
                }
            });
        }
        $bar->finish();
        $this->newLine();

        $remaining = Job::count();
        $this->info("✓ Deleted <fg=green>{$deleted}</> duplicate rows.");
        $this->info("✓ Jobs remaining: <fg=cyan>{$remaining}</>");

        return self::SUCCESS;
    }

    /**
     * Generate a stable hash for duplicate detection.
     * Same position + advertiser + location = duplicate.
     */
    public static function makeHash(?string $position, $advertiserId, $locationId): ?string
    {
        $position = trim((string) $position);
        if ($position === '') {
            return null; // jobs without a position can't be deduped reliably
        }

        $key = mb_strtolower($position) . '|' . ($advertiserId ?? '0') . '|' . ($locationId ?? '0');

        return hash('sha256', $key);
    }
}
