<?php

use App\Models\Blog;
use Database\Seeders\ConstructionJobsSaudiBlogSeeder;
use Database\Seeders\FrontEndDeveloperJobsUsaBlogSeeder;
use Database\Seeders\MarksAndSpencerRetailJobsUkBlogSeeder;
use Database\Seeders\ReactDeveloperJobsUsaBlogSeeder;

/**
 * Search Console showed these three pages earning impressions on queries whose
 * head term was missing from the title Google renders in the result: the React
 * guide took five salary queries under a title that only said "Jobs", and the
 * Marks and Spencer guide took "m and s careers" under a title with no
 * "Careers" in it. The contract here is that the rendered title carries the
 * term the impressions are actually earned on.
 *
 * @var array<string, array{0: class-string, 1: string, 2: list<string>}>
 */
dataset('pages retitled from search console', [
    'react developer salary usa' => [
        ReactDeveloperJobsUsaBlogSeeder::class,
        'react-developer-jobs-in-usa',
        // Job intent leads, because that is the page and the slug; "Salary"
        // rides along because every USA query it takes is a salary query.
        ['React', 'Jobs', 'Salary', 'USA'],
    ],
    'frontend developer jobs usa' => [
        FrontEndDeveloperJobsUsaBlogSeeder::class,
        'front-end-developer-jobs-in-usa',
        // The body and slug say "Front End"; the title carries the one-word
        // spelling people actually type, so the page covers both.
        ['Frontend', 'Jobs', 'USA'],
    ],
    'm and s careers uk' => [
        MarksAndSpencerRetailJobsUkBlogSeeder::class,
        'how-to-apply-for-marks-and-spencer-retail-jobs-in-the-uk',
        ['M and S', 'Careers', 'UK'],
    ],
    'construction jobs saudi arabia visa sponsorship' => [
        ConstructionJobsSaudiBlogSeeder::class,
        'construction-jobs-in-saudi-arabia-with-visa-sponsorship',
        ['Construction Jobs', 'Saudi Arabia', 'Visa Sponsorship'],
    ],
]);

it('titles each page on the term its impressions are earned on', function (string $seeder, string $slug, array $terms): void {
    $this->seed($seeder);

    $metaTitle = Blog::where('slug', $slug)->value('meta_title');

    foreach ($terms as $term) {
        expect($metaTitle)->toContain($term);
    }
})->with('pages retitled from search console');

it('keeps the whole rendered title inside the snippet limit', function (string $seeder, string $slug): void {
    $this->seed($seeder);

    $blog = Blog::where('slug', $slug)->first();

    // blog-post.blade.php yields meta_title verbatim with no " | JobGader"
    // suffix, so this string is the entire <title> element.
    expect(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
})->with('pages retitled from search console');

it('leaves the ranking URL where it is while the title changes', function (string $seeder, string $slug): void {
    $this->seed($seeder);

    $metaTitle = Blog::where('slug', $slug)->value('meta_title');

    // The slug is Str::slug($title), so retitling through $title rather than
    // meta_title would move the URL and throw away the position these three
    // already hold. A 404 here is that mistake.
    $this->get('/blog/'.$slug)
        ->assertOk()
        ->assertSee('<title>'.$metaTitle.'</title>', false);
})->with('pages retitled from search console');

it('keeps the title Google renders free of characters that need escaping', function (string $seeder, string $slug): void {
    $this->seed($seeder);

    // master.blade.php yields the title unescaped, so an ampersand or angle
    // bracket would land in the <title> as invalid markup.
    $metaTitle = Blog::where('slug', $slug)->value('meta_title');

    expect($metaTitle)->not->toMatch('/[&<>"]/')
        ->and(mb_check_encoding($metaTitle, 'ASCII'))->toBeTrue();
})->with('pages retitled from search console');
