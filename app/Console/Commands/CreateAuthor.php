<?php

namespace App\Console\Commands;

use App\Models\Author;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateAuthor extends Command
{
    protected $signature = 'authors:create
        {name : The author\'s real name}
        {--title= : Job title or role shown under the name}
        {--bio= : A short real bio}
        {--photo= : Path of the photo inside storage/app/public, for example authors/name.jpg}
        {--default : Make this the author given to every new post}';

    protected $description = 'Create an author profile for the blog';

    public function handle(): int
    {
        $name = trim((string) $this->argument('name'));
        $slug = Str::slug($name);

        if ($slug === '') {
            $this->error('The name must contain letters.');

            return self::FAILURE;
        }

        $author = Author::updateOrCreate(['slug' => $slug], array_filter([
            'name' => $name,
            'job_title' => $this->option('title'),
            'bio' => $this->option('bio'),
            'photo_path' => $this->option('photo'),
        ], fn ($value) => $value !== null));

        if ($this->option('default')) {
            Author::where('id', '!=', $author->id)->update(['is_default' => false]);
            $author->update(['is_default' => true]);
        }

        $this->info("Author {$author->name} saved (slug {$author->slug}).");

        return self::SUCCESS;
    }
}
