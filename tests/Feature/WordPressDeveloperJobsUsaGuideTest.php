<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\ReactDeveloperJobsUsaBlogSeeder;
use Database\Seeders\WebDeveloperJobsUsaBlogSeeder;
use Database\Seeders\WordPressDeveloperJobsUsaBlogSeeder;

use function Pest\Laravel\get;

const WORDPRESS_SLUG = 'wordpress-developer-jobs-in-usa';

const WORDPRESS_APPLY_URL = 'https://www.indeed.com/q-wordpress-developer-jobs.html';

beforeEach(function () {
    $this->seed(WordPressDeveloperJobsUsaBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', WORDPRESS_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/wordpress-developer-jobs-in-usa.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        // blogs.excerpt is a varchar(255); sqlite accepts more, MySQL does not.
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', WORDPRESS_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->toHaveCount(2);

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('strips the search-preview parameter the draft apply link carried', function () {
    // The draft link was ?vjk=d3edf92276ccc7ed on a non-canonical path.
    $blog = Blog::where('slug', WORDPRESS_SLUG)->first();

    expect($blog->content)->toContain(WORDPRESS_APPLY_URL)
        ->and($blog->content)->not->toContain('vjk=')
        ->and($blog->content)->not->toContain('q-usa-wordpress-developer-jobs');
});

it('anchors pay on the web developer occupation the roles are counted in', function () {
    $body = Blog::where('slug', WORDPRESS_SLUG)->value('content');

    expect($body)->toContain('$92,650')
        ->toContain('$48,100')
        ->toContain('$162,290');
});

it('explains the lower bands instead of inflating them', function () {
    // The draft's bands are below the rest of the cluster. They are accurate,
    // so the page keeps them and gives the reason.
    $response = get('/blog/'.WORDPRESS_SLUG)->assertOk();

    $response->assertSee('$50,000 to $75,000', false)
        ->assertSee('Pay in web work tracks how easily you can be replaced', false)
        ->assertSee('the most substitutable work in web development', false);
});

it('carries the retainer economics that no other guide in the cluster covers', function () {
    // This page's differentiator, and absent from the draft entirely.
    $body = Blog::where('slug', WORDPRESS_SLUG)->value('content');

    expect($body)->toContain('The Retainer Is the Business Model')
        ->toContain('Backups that have been restored at least once')
        ->toContain('capped in hours')
        ->toContain('who carries the cost if the site is compromised');
});

it('gives the vulnerability figures that make a care plan worth buying', function () {
    $response = get('/blog/'.WORDPRESS_SLUG)->assertOk();

    $response->assertSee('11,334 new vulnerabilities', false)
        ->assertSee('42 per cent increase on 2024', false)
        ->assertSee('91 per cent were in plugins', false);
});

it('reports the market share honestly, including the decline', function () {
    // Most WordPress careers content quotes the share and stops. The direction
    // is what makes the advice actionable.
    $body = Blog::where('slug', WORDPRESS_SLUG)->value('content');

    expect($body)->toContain('40.7 per cent of all websites')
        ->toContain('58.9 per cent of the CMS market')
        ->toContain('the share has been slipping');
});

it('defers performance and freelance tax to the guides that argue them', function () {
    $body = Blog::where('slug', WORDPRESS_SLUG)->value('content');

    expect($body)->toContain('/blog/front-end-developer-jobs-in-usa')
        ->toContain('/blog/web-developer-jobs-in-usa')
        // Argued at length in the front end guide, not repeated here.
        ->and($body)->not->toContain('26 April 2027')
        ->and($body)->not->toContain('200 milliseconds');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', WORDPRESS_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Elementor developer jobs');

    get('/blog/'.WORDPRESS_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    expect(app(StructuredDataService::class)->describesSingleVacancy(WORDPRESS_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.WORDPRESS_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one US listing and does not duplicate it on a re-run', function () {
    $this->seed(WordPressDeveloperJobsUsaBlogSeeder::class);

    $jobs = Job::where('application_url', WORDPRESS_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', WORDPRESS_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('United States')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('backups that have actually been restored at least once');
});

it('links into the web cluster in both directions', function () {
    $this->seed(WebDeveloperJobsUsaBlogSeeder::class);
    $this->seed(ReactDeveloperJobsUsaBlogSeeder::class);

    get('/blog/'.WORDPRESS_SLUG)->assertOk()
        ->assertSee('/blog/web-developer-jobs-in-usa', false)
        ->assertSee('/blog/front-end-developer-jobs-in-usa', false)
        ->assertSee('/blog/react-developer-jobs-in-usa', false)
        ->assertSee('/blog/digital-marketing-jobs-in-usa', false);

    get('/blog/web-developer-jobs-in-usa')->assertOk()
        ->assertSee('/blog/'.WORDPRESS_SLUG, false);

    get('/blog/react-developer-jobs-in-usa')->assertOk()
        ->assertSee('/blog/'.WORDPRESS_SLUG, false);
});
