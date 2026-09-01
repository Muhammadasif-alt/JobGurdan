<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Services\JobKeywordExtractor;
use Illuminate\Console\Command;

/**
 * Backfill seo_keywords + meta_description for existing jobs that
 * were imported before the keyword extractor existed. Runs in chunks
 * so it's safe on tables with millions of rows.
 */
class BackfillJobKeywords extends Command
{
    protected $signature = 'jobs:backfill-keywords
                            {--chunk=200 : Rows per chunk}
                            {--all      : Re-extract even for jobs that already have keywords}';

    protected $description = 'Extract seo_keywords + meta_description for existing jobs.';

    public function handle(JobKeywordExtractor $extractor): int
    {
        $chunk = max(50, (int) $this->option('chunk'));
        $all = (bool) $this->option('all');

        $query = Job::query()->select(['id', 'position', 'description', 'location_id', 'seo_keywords']);
        if (! $all) {
            $query->where(function ($q) {
                $q->whereNull('seo_keywords')->orWhere('seo_keywords', '');
            });
        }

        $total = (clone $query)->count();
        if ($total === 0) {
            $this->info('Nothing to backfill — all jobs already have keywords.');

            return self::SUCCESS;
        }

        $this->info("Backfilling keywords for {$total} jobs (chunk={$chunk})...");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $updated = 0;
        $query->with('location:id,name')->chunkById($chunk, function ($jobs) use ($extractor, &$updated, $bar) {
            foreach ($jobs as $job) {
                $kw = $extractor->extract($job->position, $job->description);
                $meta = $extractor->buildMetaDescription($job->position, $job->description, optional($job->location)->name);

                $job->seo_keywords = $kw ? implode(', ', $kw) : null;
                $job->meta_description = $meta !== '' ? $meta : null;
                // saveQuietly to skip the dedupe-hash regeneration boot hook
                $job->saveQuietly();
                $updated++;
                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info("Done — {$updated} jobs updated.");

        return self::SUCCESS;
    }
}
