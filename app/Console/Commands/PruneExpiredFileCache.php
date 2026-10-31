<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;

class PruneExpiredFileCache extends Command
{
    protected $signature = 'cache:prune-expired-files
        {--dry-run : Show what would go without deleting}';

    protected $description = 'Delete file cache entries past their expiry. Laravel only removes one when it is read again, so unread entries pile up.';

    public function handle(): int
    {
        $path = config('cache.stores.file.path');

        if (! is_dir($path)) {
            $this->info('No file cache directory.');

            return self::SUCCESS;
        }

        $deleted = 0;
        $freed = 0;

        foreach (Finder::create()->files()->ignoreDotFiles(true)->in($path) as $file) {
            $handle = @fopen($file->getPathname(), 'r');

            if ($handle === false) {
                continue;
            }

            // A cache file starts with its ten-digit expiry timestamp; an empty one is a failed write.
            $expiry = fread($handle, 10);
            fclose($handle);

            if ($expiry !== '' && $expiry !== false && (int) $expiry >= time()) {
                continue;
            }

            $freed += $file->getSize();
            $deleted++;

            if (! $this->option('dry-run')) {
                @unlink($file->getPathname());
            }
        }

        $this->info(($this->option('dry-run') ? 'Would delete ' : 'Deleted ').$deleted.' expired cache files, '.round($freed / 1048576, 1).' MB.');

        return self::SUCCESS;
    }
}
