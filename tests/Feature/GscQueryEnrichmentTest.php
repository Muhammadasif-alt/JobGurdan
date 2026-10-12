<?php

use App\Models\Blog;
use App\Models\BlogCatgories;
use Database\Seeders\GscQueryEnrichmentSeeder;

function enrichableBlog(string $slug, string $content, string $tags = 'jobs usa, salary'): Blog
{
    $category = BlogCatgories::firstOrCreate(['slug' => 'jobs'], ['name' => 'Jobs', 'description' => 'x']);

    return Blog::create([
        'blog_catgories_id' => $category->id,
        'author_name' => 'Admin',
        'title' => 'Guide '.$slug,
        'slug' => $slug,
        'excerpt' => 'Excerpt',
        'content' => $content,
        'tags' => $tags,
        'featured_image' => 'blogs/x.jpg',
        'meta_title' => 'Guide',
        'meta_description' => 'Guide',
        'reading_time' => 3,
        'status' => 'published',
        'published_at' => '2026-10-01',
    ]);
}

it('adds the salary section before the FAQ and bolds the searched phrases', function () {
    $blog = enrichableBlog(
        'react-developer-jobs-in-usa',
        '<p>Intro</p><h2>Frequently Asked Questions</h2><h3>Q?</h3><p>A</p><h2>People Also Search For</h2><h3>X</h3><p>Y</p>'
    );

    (new GscQueryEnrichmentSeeder)->run();

    $content = $blog->fresh()->content;

    expect($content)->toContain(GscQueryEnrichmentSeeder::MARKER)
        ->toContain('<strong>react developer salary</strong>')
        ->toContain('<strong>average react developer salary in the US</strong>')
        ->toContain('<strong>react developer salary by state</strong>')
        ->toContain('$135,980')
        ->toContain('$174,410')
        ->toContain('PKR 38,074,400')
        ->toContain('US Bureau of Labor Statistics');

    expect(strpos($content, GscQueryEnrichmentSeeder::MARKER))->toBeLessThan(strpos($content, '<h2>Frequently Asked Questions</h2>'))
        ->and(substr_count($content, 'Frequently Asked Questions'))->toBe(1);
});

it('does not add the section twice and merges tags without duplicates', function () {
    $blog = enrichableBlog('cashier-jobs-in-usa', '<p>Intro</p><h2>More Job Guides</h2><p>x</p>', 'cashier jobs usa, Cashier Pay USA');

    (new GscQueryEnrichmentSeeder)->run();
    (new GscQueryEnrichmentSeeder)->run();

    $fresh = $blog->fresh();
    $tags = array_map('trim', explode(',', $fresh->tags));

    expect(substr_count($fresh->content, GscQueryEnrichmentSeeder::MARKER))->toBe(1)
        ->and($tags)->toContain('cashier jobs usa')
        ->and(array_count_values(array_map('strtolower', $tags))['cashier pay usa'])->toBe(1)
        ->and($tags)->toContain('cashier salary usa')
        ->and(strlen($fresh->tags))->toBeLessThanOrEqual(240);
});

it('appends the section when the post has no FAQ heading', function () {
    $blog = enrichableBlog('truck-driver-jobs-in-usa-with-visa-sponsorship', '<p>Only an intro.</p>');

    (new GscQueryEnrichmentSeeder)->run();

    expect($blog->fresh()->content)->toStartWith('<p>Only an intro.</p>')
        ->toContain('$58,640')
        ->toContain('truck driver visa sponsorship');
});

it('skips slugs that are not in the database', function () {
    (new GscQueryEnrichmentSeeder)->run();

    expect(Blog::count())->toBe(0);
});

it('uses only known wage rows and internal links in every entry', function () {
    $seeder = new GscQueryEnrichmentSeeder;
    $entries = (new ReflectionMethod($seeder, 'entries'))->invoke($seeder);
    $wages = (new ReflectionClass($seeder))->getConstant('WAGES');

    expect($entries)->not->toBeEmpty();

    foreach ($entries as $entry) {
        foreach ($entry['wages'] as $key) {
            expect($wages)->toHaveKey($key);
        }

        foreach ($entry['links'] as [$href]) {
            expect($href)->toStartWith('/');
        }

        expect(substr_count($entry['intro'], '<strong>'))->toBeGreaterThanOrEqual(1)
            ->and(strtolower($entry['intro'].$entry['note']))->not->toContain('indeed');

        foreach ($entry['tags'] as $tag) {
            expect(mb_check_encoding($tag, 'ASCII'))->toBeTrue();
        }
    }

    expect(array_column($entries, 'slug'))->toBe(array_unique(array_column($entries, 'slug')));
});
