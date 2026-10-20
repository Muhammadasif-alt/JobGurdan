<?php

namespace App\Console\Commands;

use App\Models\Blog;
use Illuminate\Console\Command;

/**
 * Sets or clears the per-post noindex flag. Posts stay published and
 * reachable; only the robots meta tag and the blog sitemap change.
 */
class NoindexBlogPosts extends Command
{
    protected $signature = 'blogs:noindex
        {slugs?* : Post slugs to change}
        {--csv= : A reviewed audit CSV; every row whose suggested_action is exactly "noindex" is used}
        {--undo : Clear the flag instead of setting it}
        {--dry-run : Show what would change without saving}';

    protected $description = 'Mark blog posts noindex (or clear it) without unpublishing or deleting them.';

    public function handle(): int
    {
        $slugs = collect($this->argument('slugs'));

        if ($csv = $this->option('csv')) {
            if (! is_readable($csv)) {
                $this->error("Cannot read {$csv}.");

                return self::FAILURE;
            }

            $slugs = $slugs->merge($this->slugsFromCsv($csv));
        }

        $slugs = $slugs->filter()->unique()->values();

        if ($slugs->isEmpty()) {
            $this->error('Give at least one slug or a --csv file.');

            return self::FAILURE;
        }

        $value = ! $this->option('undo');
        $query = Blog::whereIn('slug', $slugs)->where('noindex', ! $value);
        $count = $query->count();

        if ($this->option('dry-run')) {
            $this->info("{$count} posts would be ".($value ? 'set to noindex' : 'set back to indexable').'.');

            return self::SUCCESS;
        }

        $query->update(['noindex' => $value]);
        $this->info("{$count} posts ".($value ? 'set to noindex' : 'set back to indexable').'.');

        return self::SUCCESS;
    }

    /**
     * @return array<int, string>
     */
    private function slugsFromCsv(string $path): array
    {
        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);
        $slugAt = array_search('slug', $header, true);
        $actionAt = array_search('suggested_action', $header, true);
        $slugs = [];

        while (($row = fgetcsv($handle)) !== false) {
            if ($slugAt !== false && $actionAt !== false && trim($row[$actionAt] ?? '') === 'noindex') {
                $slugs[] = $row[$slugAt];
            }
        }

        fclose($handle);

        return $slugs;
    }
}
