<?php

namespace App\Console\Commands;

use App\Models\Job;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Job lifecycle:
 *   1. Optional: mark active jobs older than --expire-days as expired. Off by
 *      default (0), because a job with no expiry date stays live however old it
 *      is. Only jobs whose own status is already "expired" are removed.
 *   2. Hard-delete jobs that have been expired for more than --delete-days
 *      (counted from when they were marked, default 7).
 *
 * saved_jobs.job_id has cascadeOnDelete so DELETE here is FK-safe.
 */
class ExpireOldJobs extends Command
{
    protected $signature = 'jobs:expire-old
                            {--expire-days=0 : Active jobs older than this many days become expired (0 = never age jobs out)}
                            {--delete-days=7 : Jobs expired for longer than this many days are deleted from DB}
                            {--skip-expire : Skip the mark-expired pass}
                            {--skip-delete : Skip the delete-old pass}
                            {--dry-run : Show counts without writing}';

    protected $description = 'Delete jobs that are already expired (stage 2). Ageing out by created_at (stage 1) only runs when --expire-days is set.';

    public function handle(): int
    {
        $expireDays = (int) $this->option('expire-days');
        $deleteDays = (int) $this->option('delete-days');
        $dryRun = (bool) $this->option('dry-run');
        $skipExpire = (bool) $this->option('skip-expire');
        $skipDelete = (bool) $this->option('skip-delete');

        if ($expireDays > 0 && $deleteDays < $expireDays) {
            $this->error("--delete-days ({$deleteDays}) must be >= --expire-days ({$expireDays}). Otherwise jobs would be deleted before they're even marked expired.");

            return self::FAILURE;
        }

        // Stage 1: mark active old jobs as expired.
        if (! $skipExpire && $expireDays > 0) {
            $this->expirePass($expireDays, $dryRun);
        }

        // Stage 2: hard-delete long-expired jobs to free DB space.
        if (! $skipDelete) {
            $this->deletePass($deleteDays, $dryRun);
        }

        return self::SUCCESS;
    }

    protected function expirePass(int $days, bool $dryRun): void
    {
        $cutoff = now()->subDays($days);

        $query = Job::query()
            ->where('created_at', '<', $cutoff)
            ->where(function ($q) {
                $q->where('status', 'active')->orWhereNull('status');
            });

        $count = $query->count();

        if ($count === 0) {
            $this->info("[expire] No active jobs older than {$days} days.");

            return;
        }

        if ($dryRun) {
            $this->info("[expire] [dry-run] {$count} jobs would be marked expired (older than {$cutoff->toDateString()}).");

            return;
        }

        $updated = $query->update(['status' => 'expired']);
        $this->info("[expire] Marked {$updated} jobs as expired (older than {$cutoff->toDateString()}).");
    }

    protected function deletePass(int $days, bool $dryRun): void
    {
        $cutoff = now()->subDays($days);

        // Use query builder for batched DELETE — faster than Eloquent and avoids
        // hydrating 100k models. FK cascades on saved_jobs still fire at DB level.
        $baseQuery = DB::table('jobs')
            ->where('status', 'expired')
            ->where('updated_at', '<', $cutoff);

        $count = $baseQuery->count();

        if ($count === 0) {
            $this->info("[delete] No expired jobs older than {$days} days.");

            return;
        }

        if ($dryRun) {
            $this->info("[delete] [dry-run] {$count} expired jobs would be deleted (older than {$cutoff->toDateString()}).");

            return;
        }

        // Delete in batches of 5,000 so a single DELETE never blows past
        // max_statement_time on Hostinger's shared MariaDB (60s cap).
        $totalDeleted = 0;
        do {
            $deleted = DB::table('jobs')
                ->where('status', 'expired')
                ->where('updated_at', '<', $cutoff)
                ->limit(5000)
                ->delete();
            $totalDeleted += $deleted;
            if ($deleted > 0) {
                $this->info("[delete] Batch removed {$deleted} (running total: {$totalDeleted}).");
            }
        } while ($deleted > 0);

        $this->info("[delete] Deleted {$totalDeleted} expired jobs older than {$cutoff->toDateString()}.");
    }
}
