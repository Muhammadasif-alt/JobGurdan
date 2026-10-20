<?php

use App\Models\Blog;
use App\Models\BlogCatgories;
use Illuminate\Support\Facades\Artisan;

function auditPost(string $slug, string $title, string $content, array $overrides = []): Blog
{
    $category = BlogCatgories::firstOrCreate(['slug' => 'visa-sponsorship'], ['name' => 'Visa Sponsorship', 'description' => 'Guides']);

    return Blog::create(array_merge([
        'blog_catgories_id' => $category->id,
        'author_name' => 'Admin',
        'title' => $title,
        'slug' => $slug,
        'excerpt' => 'Excerpt.',
        'content' => $content,
        'status' => 'published',
        'published_at' => now(),
    ], $overrides));
}

function longBody(int $words, bool $withSources): string
{
    $body = '<p>'.implode(' ', array_fill(0, $words, 'word')).'</p>';

    return $withSources
        ? $body.'<h2>Sources</h2><ol><li><a href="https://www.gov.uk/work">GOV.UK</a></li><li><a href="https://www.uscis.gov/">USCIS</a></li></ol>'
        : $body;
}

it('exports every post with counts and a suggested action, and changes nothing', function () {
    auditPost('strong-guide', 'Warehouse Worker Jobs in UK With Visa Sponsorship', longBody(900, true));
    auditPost('thin-guide', 'Picker Jobs Abroad', longBody(120, false));
    $before = Blog::count();
    $path = storage_path('app/blog-audit-test.csv');

    Artisan::call('blogs:audit', ['--path' => $path]);

    $rows = array_map('str_getcsv', file($path, FILE_IGNORE_NEW_LINES));
    $header = array_shift($rows);
    $byslug = collect($rows)->mapWithKeys(fn ($r) => [$r[array_search('slug', $header)] => array_combine($header, $r)]);

    expect($byslug['strong-guide']['source_links'])->toBe('2')
        ->and($byslug['strong-guide']['has_sources_section'])->toBe('yes')
        ->and($byslug['strong-guide']['suggested_action'])->toBe('keep')
        ->and($byslug['thin-guide']['flags'])->toContain('short')->toContain('no_sources')
        ->and($byslug['thin-guide']['suggested_action'])->toBe('noindex')
        ->and(Blog::count())->toBe($before)
        ->and(Blog::where('noindex', true)->count())->toBe(0);

    @unlink($path);
});

it('points a near duplicate title at the stronger post', function () {
    auditPost('uk-warehouse-visa', 'Warehouse Worker Jobs in UK With Visa Sponsorship', longBody(1500, true));
    auditPost('uk-warehouse-visa-2', 'Warehouse Worker Jobs UK Visa Sponsorship 2026', longBody(1000, true));
    $path = storage_path('app/blog-audit-test2.csv');

    Artisan::call('blogs:audit', ['--path' => $path]);

    $rows = array_map('str_getcsv', file($path, FILE_IGNORE_NEW_LINES));
    $header = array_shift($rows);
    $byslug = collect($rows)->mapWithKeys(fn ($r) => [$r[array_search('slug', $header)] => array_combine($header, $r)]);

    expect($byslug['uk-warehouse-visa']['suggested_action'])->toBe('keep')
        ->and($byslug['uk-warehouse-visa-2']['suggested_action'])->toBe('merge into uk-warehouse-visa');

    @unlink($path);
});

it('noindexes a post without unpublishing it, and removes it from the blog sitemap', function () {
    $post = auditPost('weak-post', 'Weak Post About Jobs', longBody(100, false));
    $keep = auditPost('good-post', 'A Different Good Guide', longBody(900, true));

    $this->get('/blog/weak-post')->assertOk()->assertDontSee('content="noindex, follow"', false);

    Artisan::call('blogs:noindex', ['slugs' => ['weak-post']]);

    expect($post->fresh()->noindex)->toBeTrue()
        ->and($post->fresh()->status)->toBe('published');

    $this->get('/blog/weak-post')->assertOk()->assertSee('content="noindex, follow"', false);
    $this->get('/sitemap-blog.xml')->assertDontSee('/blog/weak-post', false)->assertSee('/blog/good-post', false);

    Artisan::call('blogs:noindex', ['slugs' => ['weak-post'], '--undo' => true]);
    expect($post->fresh()->noindex)->toBeFalse();
});

it('reads noindex decisions from a reviewed csv', function () {
    auditPost('csv-one', 'First Weak Post', longBody(100, false));
    auditPost('csv-two', 'Second Strong Guide', longBody(900, true));
    $path = storage_path('app/blog-noindex-test.csv');
    file_put_contents($path, "slug,suggested_action\ncsv-one,noindex\ncsv-two,keep\n");

    Artisan::call('blogs:noindex', ['--csv' => $path]);

    expect(Blog::where('slug', 'csv-one')->value('noindex'))->toBeTrue()
        ->and(Blog::where('slug', 'csv-two')->value('noindex'))->toBeFalse();

    @unlink($path);
});

it('gives a blog category page a canonical that points at itself', function () {
    auditPost('cat-post', 'A Visa Guide', longBody(900, true));

    $this->get('/blog?category=visa-sponsorship')
        ->assertOk()
        ->assertSee('<link rel="canonical" href="'.route('blog.index', ['category' => 'visa-sponsorship']).'">', false);

    $this->get('/blog')
        ->assertOk()
        ->assertSee('<link rel="canonical" href="'.route('blog.index').'">', false);
});
