<?php

namespace App\Console\Commands;

use App\Models\Author;
use App\Models\Blog;
use Illuminate\Console\Command;

class SpreadAuthors extends Command
{
    protected $signature = 'authors:spread
        {slugs* : Slugs of the authors to share the posts between, in rotation order}
        {--dry-run : Show how many posts each author would get without saving}';

    protected $description = 'Share every post between the named authors in turn, oldest post first. Nothing is deleted.';

    public function handle(): int
    {
        $slugs = $this->argument('slugs');
        $authors = Author::whereIn('slug', $slugs)->get()->sortBy(fn (Author $author) => array_search($author->slug, $slugs))->values();

        if ($authors->count() !== count(array_unique($slugs))) {
            $this->error('Unknown author slug. Create the authors first with authors:create.');

            return self::FAILURE;
        }

        $counts = array_fill(0, $authors->count(), 0);
        $position = 0;

        Blog::query()->orderBy('id')->select(['id', 'author_profile_id'])->chunkById(200, function ($blogs) use ($authors, &$counts, &$position) {
            foreach ($blogs as $blog) {
                $index = $position++ % $authors->count();
                $counts[$index]++;

                if ($this->option('dry-run')) {
                    continue;
                }

                // toBase() leaves updated_at alone: a byline change is not a content update.
                Blog::whereKey($blog->id)->toBase()->update([
                    'author_profile_id' => $authors[$index]->id,
                    'author_name' => $authors[$index]->name,
                ]);
            }
        });

        foreach ($authors as $index => $author) {
            $this->info(($this->option('dry-run') ? 'Would give ' : 'Gave ').$counts[$index].' posts to '.$author->name.'.');
        }

        return self::SUCCESS;
    }
}
