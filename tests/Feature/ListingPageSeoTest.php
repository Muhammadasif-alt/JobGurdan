<?php

use App\Models\Advertiser;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Job;
use App\Models\Location;
use Database\Seeders\FerrariFactoryJobsItalyBlogSeeder;

/**
 * The listing pages — locations, companies, categories, the jobs index, the
 * blog archive and the search results — carried no structured data, shared a
 * single fallback meta description between roughly 190 URLs, and canonicalised
 * every paginated page back to page 1. These tests pin the fixes.
 */

/**
 * @return list<array<string, mixed>>
 */
function jsonLdBlocks(string $html): array
{
    preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $matches);

    return collect($matches[1])
        ->map(fn (string $block) => json_decode(trim($block), true))
        ->filter(fn ($decoded) => is_array($decoded))
        ->values()
        ->all();
}

function jsonLdOfType(string $html, string $type): ?array
{
    foreach (jsonLdBlocks($html) as $block) {
        if (($block['@type'] ?? null) === $type) {
            return $block;
        }
    }

    return null;
}

function metaContent(string $html, string $name): ?string
{
    if (preg_match('/<meta name="'.preg_quote($name, '/').'" content="([^"]*)"/', $html, $m)) {
        return html_entity_decode($m[1], ENT_QUOTES);
    }

    return null;
}

function propertyContent(string $html, string $property): ?string
{
    if (preg_match('/<meta property="'.preg_quote($property, '/').'" content="([^"]*)"/', $html, $m)) {
        return html_entity_decode($m[1], ENT_QUOTES);
    }

    return null;
}

function canonicalOf(string $html): ?string
{
    if (preg_match('/<link rel="canonical" href="([^"]*)"/', $html, $m)) {
        return html_entity_decode($m[1], ENT_QUOTES);
    }

    return null;
}

function titleOf(string $html): ?string
{
    if (preg_match('/<title>(.*?)<\/title>/s', $html, $m)) {
        return html_entity_decode(trim($m[1]), ENT_QUOTES);
    }

    return null;
}

/** The description every page fell back to when it authored none of its own. */
function sharedFallbackDescription(): string
{
    return 'Find hand-checked job openings across';
}

beforeEach(function () {
    $this->seed(FerrariFactoryJobsItalyBlogSeeder::class);

    $this->job = Job::query()->where('position', 'like', 'Factory Operator, Ferrari%')->firstOrFail();
    $this->location = Location::query()->findOrFail($this->job->location_id);
    $this->company = Advertiser::query()->findOrFail($this->job->advertiser_id);
    $this->category = Category::query()->findOrFail($this->job->category_id);
});

it('gives a location page its own description instead of the site-wide fallback', function () {
    $html = $this->get('/location/'.$this->location->id)->assertOk()->getContent();

    $description = metaContent($html, 'description');

    expect($description)->not->toBeNull()
        ->and($description)->not->toContain(sharedFallbackDescription())
        ->and($description)->toContain($this->location->name);
});

it('gives a company page its own description naming the employer', function () {
    $html = $this->get('/companies/'.$this->company->id)->assertOk()->getContent();

    $description = metaContent($html, 'description');

    expect($description)->not->toBeNull()
        ->and($description)->not->toContain(sharedFallbackDescription())
        ->and($description)->toContain($this->company->name);
});

it('publishes breadcrumbs and a job collection on the location page', function () {
    $html = $this->get('/location/'.$this->location->id)->assertOk()->getContent();

    $breadcrumbs = jsonLdOfType($html, 'BreadcrumbList');
    $collection = jsonLdOfType($html, 'CollectionPage');

    expect($breadcrumbs)->not->toBeNull()
        ->and(collect($breadcrumbs['itemListElement'])->pluck('name')->all())
        ->toBe(['Home', 'Locations', $this->location->name])
        ->and($collection)->not->toBeNull()
        ->and($collection['mainEntity']['@type'])->toBe('ItemList')
        ->and($collection['mainEntity']['numberOfItems'])->toBeGreaterThan(0)
        ->and($collection['mainEntity']['itemListElement'][0]['url'])->toContain('/jobs/');
});

it('publishes breadcrumbs and the employer organization on the company page', function () {
    $html = $this->get('/companies/'.$this->company->id)->assertOk()->getContent();

    $breadcrumbs = jsonLdOfType($html, 'BreadcrumbList');
    $collection = jsonLdOfType($html, 'CollectionPage');

    expect($breadcrumbs)->not->toBeNull()
        ->and(collect($breadcrumbs['itemListElement'])->pluck('name')->all())
        ->toBe(['Home', 'Companies', $this->company->name])
        ->and($collection)->not->toBeNull()
        ->and($collection['about']['@type'])->toBe('Organization')
        ->and($collection['about']['name'])->toBe($this->company->name);
});

it('publishes breadcrumbs and a collection on the category page', function () {
    $html = $this->get('/categories/'.$this->category->slug)->assertOk()->getContent();

    expect(jsonLdOfType($html, 'BreadcrumbList'))->not->toBeNull()
        ->and(jsonLdOfType($html, 'CollectionPage'))->not->toBeNull();
});

it('publishes breadcrumbs and a job collection on the jobs index', function () {
    $html = $this->get('/jobs')->assertOk()->getContent();

    $collection = jsonLdOfType($html, 'CollectionPage');

    expect(jsonLdOfType($html, 'BreadcrumbList'))->not->toBeNull()
        ->and($collection)->not->toBeNull()
        ->and($collection['mainEntity']['@type'])->toBe('ItemList')
        ->and($collection['mainEntity']['itemListElement'][0]['url'])->toContain('/jobs/');
});

it('publishes breadcrumbs and a collection on the blog archive', function () {
    $html = $this->get('/blog')->assertOk()->getContent();

    expect(jsonLdOfType($html, 'BreadcrumbList'))->not->toBeNull()
        ->and(jsonLdOfType($html, 'CollectionPage'))->not->toBeNull();
});

it('keeps every category title inside the length Google renders', function () {
    Category::query()->each(function (Category $category) {
        $title = titleOf($this->get('/categories/'.$category->slug)->assertOk()->getContent());

        expect(mb_strlen((string) $title))->toBeLessThanOrEqual(60, $category->slug.' => '.$title);
    });
});

it('keeps company titles inside the length Google renders even with a long employer name', function () {
    $long = Advertiser::query()->create([
        'name' => 'International Recruitment and Staffing Solutions Group Limited',
        'type' => 'agency',
    ]);

    foreach ([$this->company->id, $long->id] as $id) {
        $title = titleOf($this->get('/companies/'.$id)->assertOk()->getContent());

        expect(mb_strlen((string) $title))->toBeLessThanOrEqual(60, $title);
    }
});

it('keeps paginated listing titles inside the length Google renders', function (string $path) {
    $title = titleOf($this->get($path.'?page=2')->assertOk()->getContent());

    expect($title)->toContain('Page 2')
        ->and(mb_strlen((string) $title))->toBeLessThanOrEqual(60, $title);
})->with([
    'jobs index' => '/jobs',
    'blog archive' => '/blog',
    'companies index' => '/companies',
]);

it('keeps job titles inside the length Google renders however long the position is', function () {
    $this->seed(Database\Seeders\KuwaitAirwaysCabinCrewJobsBlogSeeder::class);
    $this->seed(Database\Seeders\AramcoEngineeringJobsSaudiBlogSeeder::class);

    Job::query()->with('location')->get()->each(function (Job $job) {
        $slug = Str::slug($job->position.'-'.($job->location->name ?? ''));
        $title = titleOf($this->get('/jobs/'.$slug)->assertOk()->getContent());

        expect(mb_strlen((string) $title))->toBeLessThanOrEqual(60, $job->position.' => '.$title);
    });
});

it('points a paginated listing at itself rather than at page one', function (string $path) {
    $html = $this->get($path.'?page=2')->assertOk()->getContent();

    expect(canonicalOf($html))->toContain('page=2')
        ->and(titleOf($html))->toContain('Page 2');
})->with([
    'jobs index' => '/jobs',
    'blog archive' => '/blog',
]);

it('points the first page of a listing at the clean url', function (string $path) {
    $html = $this->get($path)->assertOk()->getContent();

    expect(canonicalOf($html))->not->toContain('page=')
        ->and(titleOf($html))->not->toContain('Page ');
})->with([
    'jobs index' => '/jobs',
    'blog archive' => '/blog',
    'companies index' => '/companies',
]);

it('canonicalises a paginated location page at itself', function () {
    $html = $this->get('/location/'.$this->location->id.'?page=2')->assertOk()->getContent();

    expect(canonicalOf($html))->toContain('page=2')
        ->and(titleOf($html))->toContain('Page 2');
});

it('keeps active filters out of the canonical so facets do not become their own urls', function () {
    $html = $this->get('/location/'.$this->location->id.'?job_type%5B%5D=full_time&sort=oldest&page=2')
        ->assertOk()->getContent();

    $canonical = canonicalOf($html);

    expect($canonical)->toContain('page=2')
        ->and($canonical)->not->toContain('job_type')
        ->and($canonical)->not->toContain('sort');
});

it('links the canonical location pages rather than filtered search urls', function () {
    // The location cards and the site-wide footer pointed at /search?location=
    // and /jobs?location=, both of which fold away, leaving the /location/{id}
    // pages in the sitemap with no internal links pointing at them at all.
    $html = $this->get('/locations')->assertOk()->getContent();

    expect($html)->toContain('/location/'.$this->location->id)
        ->and($html)->not->toContain('search?location=')
        ->and($html)->not->toContain('jobs?location=');
});

it('links a canonical location page from the footer of every page', function () {
    foreach (['/', '/jobs', '/blog'] as $path) {
        $html = $this->get($path)->assertOk()->getContent();

        expect($html)->toContain('/location/'.$this->location->id, $path)
            ->and($html)->not->toContain('search?location=', $path);
    }
});

it('keeps filtered search results out of the index while still following links', function () {
    $html = $this->get('/search?keywords=engineer&location=Italy')->assertOk()->getContent();

    expect(metaContent($html, 'robots'))->toBe('noindex, follow');
});

it('leaves the bare search page indexable', function () {
    $html = $this->get('/search')->assertOk()->getContent();

    expect(metaContent($html, 'robots'))->toStartWith('index, follow');
});

it('treats a page number alone as an unfiltered search', function () {
    $html = $this->get('/search?page=2')->assertOk()->getContent();

    expect(metaContent($html, 'robots'))->toStartWith('index, follow');
});

it('falls back to the page title for og and twitter rather than one shared slogan', function () {
    $html = $this->get('/location/'.$this->location->id)->assertOk()->getContent();

    $title = titleOf($html);

    expect(propertyContent($html, 'og:title'))->toBe($title)
        ->and(metaContent($html, 'twitter:title'))->toBe($title)
        ->and(propertyContent($html, 'og:url'))->toBe(canonicalOf($html))
        ->and(metaContent($html, 'twitter:card'))->toBe('summary_large_image');
});

it('renders page level scripts that were being dropped on the floor', function () {
    $html = $this->get('/companies/'.$this->company->id)->assertOk()->getContent();

    // companies-jobs pushes its selectpicker bootstrap into the scripts stack;
    // the layout never rendered that stack, so the filters were dead.
    expect($html)->toContain('selectpicker')
        ->and($html)->toContain('DOMContentLoaded');
});

it('sends the location filter to the route that actually exists', function () {
    $html = $this->get('/location/'.$this->location->id)->assertOk()->getContent();

    expect($html)->not->toContain('jobs/location');
});

it('keeps unpublished posts out of the blog sitemap', function () {
    $published = Blog::query()->where('status', 'published')->firstOrFail();

    $draft = Blog::query()->create(array_merge(
        $published->only(['title', 'content', 'excerpt', 'blog_category_id', 'author_id']),
        ['title' => 'Draft guide that must not be crawled', 'slug' => 'draft-guide-not-crawled', 'status' => 'draft'],
    ));

    $xml = $this->get('/sitemap-blog.xml')->assertOk()->getContent();

    expect($xml)->toContain('/blog/'.$published->slug)
        ->and($xml)->not->toContain('/blog/'.$draft->slug);
});

it('keeps expired jobs out of the jobs sitemap', function () {
    // A second live guide keeps the chunk non-empty; the route deliberately
    // 404s an empty chunk rather than publishing an empty <urlset>.
    $this->seed(Database\Seeders\KuwaitAirwaysCabinCrewJobsBlogSeeder::class);

    $slug = Str::slug($this->job->position.'-'.$this->location->name);

    expect($this->get('/sitemap-jobs-1.xml')->assertOk()->getContent())->toContain('/jobs/'.$slug);

    // 'status' is deliberately not mass assignable; ExpireOldJobs flips it with
    // a query builder update, so the test does the same.
    Job::query()->whereKey($this->job->id)->update(['status' => 'expired']);

    expect($this->get('/sitemap-jobs-1.xml')->assertOk()->getContent())->not->toContain('/jobs/'.$slug);
});

it('lists every live landing page in the core sitemap', function () {
    $xml = $this->get('/sitemap-core.xml')->assertOk()->getContent();

    foreach (['construction-jobs', 'it-jobs', 'software-developer-jobs', 'data-entry-jobs'] as $page) {
        // These four are routed and live but were never listed, so they were
        // reachable only by internal link. Asserting the route rather than
        // fetching the page: the landing view orders with MySQL's FIELD(),
        // which SQLite has no equivalent for.
        expect(Route::has('pages.'.$page))->toBeTrue($page.' route is missing')
            ->and($xml)->toContain('<loc>'.url('/'.$page).'</loc>');
    }
});
