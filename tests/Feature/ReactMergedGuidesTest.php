<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\EntryLevelReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\ReactTypescriptDeveloperJobsUsaBlogSeeder;

use function Pest\Laravel\get;

dataset('merged react guides', [
    'first job' => [EntryLevelReactDeveloperJobsUsaBlogSeeder::class, 'Entry Level React Developer, US Employers'],
    'interview' => [ReactTypescriptDeveloperJobsUsaBlogSeeder::class, 'React TypeScript Developer, US Employers'],
]);

it('sends each merged URL to the guide that absorbed it with a 301', function (string $old, string $new) {
    get('/blog/'.$old)->assertStatus(301)->assertRedirect('/blog/'.$new);
})->with([
    'apply' => ['how-to-apply-for-react-developer-jobs-in-usa', 'entry-level-react-developer-jobs-in-usa'],
    'internship' => ['react-developer-internship-jobs-in-usa', 'entry-level-react-developer-jobs-in-usa'],
    'javascript' => ['javascript-react-developer-jobs-in-usa', 'react-typescript-developer-jobs-in-usa'],
]);

it('redirects to a page that exists', function () {
    $this->seed(EntryLevelReactDeveloperJobsUsaBlogSeeder::class);
    $this->seed(ReactTypescriptDeveloperJobsUsaBlogSeeder::class);

    foreach (array_unique(config('blog_redirects')) as $slug) {
        get('/blog/'.$slug)->assertOk();
    }
});

it('stays inside the snippet limits and renders its FAQ markup', function (string $seeder) {
    $this->seed($seeder);
    $blog = Blog::where('slug', $seeder::SLUG)->firstOrFail();

    expect(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and(count(app(StructuredDataService::class)->faqsFromHtml($blog->content)))->toBeGreaterThanOrEqual(4);

    get('/blog/'.$blog->slug)->assertOk()->assertSee('"FAQPage"', false);
})->with('merged react guides');

it('ships every image it references', function (string $seeder) {
    $this->seed($seeder);
    $blog = Blog::where('slug', $seeder::SLUG)->firstOrFail();

    preg_match_all('#/public/storage/(blogs/[a-z0-9-]+\.jpg)#', $blog->content, $inline);

    foreach (array_merge([$blog->featured_image], $inline[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue($path.' is missing');
    }
})->with('merged react guides');

it('links no merged URL and no post of the cluster twice', function (string $seeder) {
    $this->seed($seeder);
    $content = Blog::where('slug', $seeder::SLUG)->value('content');

    foreach (array_keys(config('blog_redirects')) as $old) {
        expect($content)->not->toContain('/blog/'.$old);
    }

    $related = substr($content, strpos($content, '<h2>Related career guides</h2>'));
    $related = substr($related, 0, strpos($related, '</ul>'));
    preg_match_all('#href="(/blog/[^"]+)"#', $related, $links);

    expect($links[1])->toBe(array_values(array_unique($links[1])));
})->with('merged react guides');

it('keeps one aggregated listing and repairs the post on a re-run', function (string $seeder, string $position) {
    $this->seed($seeder);
    $this->seed($seeder);

    expect(Blog::where('slug', $seeder::SLUG)->count())->toBe(1)
        ->and(Job::where('position', $position)->count())->toBe(1);
})->with('merged react guides');
