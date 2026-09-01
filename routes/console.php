<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// =============================================================================
// Scheduled tasks
// =============================================================================
// Runs every hour — the alerts:send command itself uses the "due" scope so
// daily alerts only fire once per 24h, weekly only once per 7d. Hourly cadence
// keeps email arrival time reasonable without flooding.
//
// For this to actually run in production, the OS cron must execute:
//   * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
// =============================================================================
Schedule::command('alerts:send')
    ->hourly()
    ->withoutOverlapping(10) // 10-minute lock — long sends won't overlap
    ->onOneServer()
    ->runInBackground();

// Expire jobs older than 30 days so stale roles drop out of /jobs, category, and
// company listings. Jobs are aged out by created_at.
// Runs daily at 03:15 UTC (off-peak).
Schedule::command('jobs:expire-old')
    ->dailyAt('03:15')
    ->withoutOverlapping(20)
    ->onOneServer()
    ->runInBackground();
