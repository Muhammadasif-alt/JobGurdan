<?php

namespace App\Console\Commands;

use App\Models\Author;
use App\Models\Blog;
use Illuminate\Console\Command;

class AssignDefaultAuthor extends Command
{
    protected $signature = 'authors:assign
        {slug : Slug of the author to assign}
        {--dry-run : Show how many posts would change without saving}';

    protected $description = 'Give the chosen author every post that has no author profile yet. Nothing is deleted.';

    public function handle(): int
    {
        $author = Author::where('slug', $this->argument('slug'))->first();

        if (! $author) {
            $this->error('No author with that slug. Create one first with authors:create.');

            return self::FAILURE;
        }

        $query = Blog::whereNull('author_profile_id');
        $count = $query->count();

        if ($this->option('dry-run')) {
            $this->info("{$count} posts would be assigned to {$author->name}.");

            return self::SUCCESS;
        }

        $query->update(['author_profile_id' => $author->id, 'author_name' => $author->name]);
        $this->info("{$count} posts assigned to {$author->name}.");

        return self::SUCCESS;
    }
}
