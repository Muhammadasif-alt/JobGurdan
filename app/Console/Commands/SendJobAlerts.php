<?php

namespace App\Console\Commands;

use App\Models\JobAlert;
use App\Notifications\JobAlertMatchesNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Scans active JobAlert subscriptions and emails users their newly-matching jobs.
 *
 * Should be scheduled to run hourly. The "due" scope handles deduplication so
 * daily alerts only fire once per 24h, weekly only once per 7d.
 *
 * Run manually for testing: php artisan alerts:send --frequency=daily
 */
class SendJobAlerts extends Command
{
    protected $signature = 'alerts:send
                            {--frequency= : Only send alerts of this frequency (daily|weekly)}
                            {--dry-run    : Print matches but do not send emails or update last_sent_at}';

    protected $description = 'Send email notifications for matching jobs to subscribed users (job alerts).';

    public function handle(): int
    {
        $frequency = $this->option('frequency');
        $dryRun = (bool) $this->option('dry-run');

        $query = JobAlert::query()->due()->with(['user', 'location', 'category']);
        if ($frequency) {
            $query->where('frequency', $frequency);
        }

        $alerts = $query->get();

        if ($alerts->isEmpty()) {
            $this->info('No alerts are due right now.');

            return self::SUCCESS;
        }

        $this->info("Processing {$alerts->count()} due alert(s)...");

        $sent = 0;
        $skipped = 0;

        foreach ($alerts as $alert) {
            // Find jobs created since the last time we sent (or, if never, last 24h/7d)
            $since = $alert->last_sent_at ?? now()->sub(
                $alert->frequency === 'daily' ? '1 day' : '7 days'
            );

            $jobs = $alert->matchingJobsQuery($since)->limit(50)->get();

            if ($jobs->isEmpty()) {
                $skipped++;
                $this->line("  [skip] alert #{$alert->id} ({$alert->user->email}) — no new matches");

                continue;
            }

            $this->line("  [send] alert #{$alert->id} ({$alert->user->email}) — {$jobs->count()} matches");

            if (! $dryRun) {
                try {
                    $alert->user->notify(new JobAlertMatchesNotification($alert, $jobs));
                    $alert->update(['last_sent_at' => now()]);
                    $sent++;
                } catch (\Throwable $e) {
                    Log::error("JobAlert #{$alert->id} send failed: {$e->getMessage()}");
                    $this->error("    FAILED: {$e->getMessage()}");
                }
            } else {
                $sent++;
            }
        }

        $this->newLine();
        $this->info("Done. Sent: {$sent} · Skipped (no matches): {$skipped}".($dryRun ? ' [DRY RUN]' : ''));

        return self::SUCCESS;
    }
}
