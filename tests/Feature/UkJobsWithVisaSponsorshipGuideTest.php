<?php

use App\Models\Blog;
use App\Services\StructuredDataService;
use Database\Seeders\JobsInUkForForeignersBlogSeeder;
use Database\Seeders\UkJobsWithVisaSponsorshipBlogSeeder;
use Database\Seeders\VisaSponsorshipJobsAustraliaBlogSeeder;
use Database\Seeders\VisaSponsorshipJobsCanadaBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

const UK_SPONSORSHIP_SLUG = 'uk-jobs-with-visa-sponsorship';

it('publishes the guide with its own images and SEO fields', function () {
    $this->seed(UkJobsWithVisaSponsorshipBlogSeeder::class);

    $blog = Blog::where('slug', UK_SPONSORSHIP_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/'.UK_SPONSORSHIP_SLUG.'.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->content)->toContain(UK_SPONSORSHIP_SLUG.'-passport.jpg')
        ->and($blog->content)->toContain(UK_SPONSORSHIP_SLUG.'-register.jpg');

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }

    expect(count(array_filter(array_map('trim', explode(',', $blog->tags)))))->toBe(8);
});

it('carries exactly eight FAQs and eight People Also Search For entries', function () {
    $this->seed(UkJobsWithVisaSponsorshipBlogSeeder::class);

    $content = Blog::where('slug', UK_SPONSORSHIP_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $pasf = substr($content, (int) strpos($content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
});

it('states the sponsorship mechanics the draft left vague', function (string $claim) {
    $this->seed(UkJobsWithVisaSponsorshipBlogSeeder::class);

    expect(Blog::where('slug', UK_SPONSORSHIP_SLUG)->value('content'))->toContain($claim);
})->with([
    // Eligibility runs from the occupation code, not from the advert's wording.
    'occupation list' => 'Appendix Skilled Occupations',
    'soc codes' => 'SOC 2020',
    'medium skilled route' => 'Temporary Shortage List',
    // The salary floors, including every discounted one.
    'general threshold' => '£41,700',
    'relevant phd' => '£37,500',
    'stem phd and salary list' => '£33,400',
    'no london uplift' => 'there is no London uplift',
    // The sponsor's own cost, which is what the scams are built on.
    'skills charge large' => '£1,320',
    'skills charge small' => '£480',
    // The applicant's costs.
    'visa fee short' => '£819',
    'visa fee long' => '£1,618',
    'maintenance' => '£1,270',
    // A B-rated sponsor looks identical on the register and cannot hire.
    'sponsor rating' => 'B-rating',
    // English is B2 since 8 January 2026, with national exemptions.
    'english level' => 'B2',
    // The Certificate of Sponsorship window.
    'cos window' => 'within three months',
]);

it('tells the reader never to pay for sponsorship', function () {
    $this->seed(UkJobsWithVisaSponsorshipBlogSeeder::class);

    expect(Blog::where('slug', UK_SPONSORSHIP_SLUG)->value('content'))
        ->toContain('You never pay for sponsorship');
});

it('does not repeat superseded thresholds or the old shortage list', function () {
    $this->seed(UkJobsWithVisaSponsorshipBlogSeeder::class);

    $content = Blog::where('slug', UK_SPONSORSHIP_SLUG)->value('content');

    expect($content)->not->toContain('£26,200')
        ->and($content)->not->toContain('£38,700')
        ->and($content)->not->toContain('shortage occupation list');
});

it('links to no aggregator', function () {
    $this->seed(UkJobsWithVisaSponsorshipBlogSeeder::class);

    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', (string) Blog::where('slug', UK_SPONSORSHIP_SLUG)->value('content'), $matches);

    foreach ($matches[1] as $href) {
        $host = strtolower((string) parse_url($href, PHP_URL_HOST));

        foreach (['indeed', 'ziprecruiter', 'glassdoor', 'simplyhired', 'monster', 'totaljobs', 'reed', 'cv-library'] as $label) {
            expect(explode('.', $host))->not->toContain($label);
        }
    }
});

it('renders the page with its long-tail sections', function () {
    $this->seed(UkJobsWithVisaSponsorshipBlogSeeder::class);

    get('/blog/'.UK_SPONSORSHIP_SLUG)
        ->assertOk()
        ->assertSee('UK Jobs with Visa Sponsorship')
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('cross-links with the rest of the sponsorship cluster in both directions', function (string $seeder, string $slug) {
    $this->seed(UkJobsWithVisaSponsorshipBlogSeeder::class);
    $this->seed($seeder);

    expect(Blog::where('slug', UK_SPONSORSHIP_SLUG)->value('content'))
        ->toContain('/blog/'.$slug)
        ->and(Blog::where('slug', $slug)->value('content'))
        ->toContain('/blog/'.UK_SPONSORSHIP_SLUG);
})->with([
    'uk hub' => [JobsInUkForForeignersBlogSeeder::class, 'jobs-in-uk-for-foreigners'],
    'australia' => [VisaSponsorshipJobsAustraliaBlogSeeder::class, 'visa-sponsorship-jobs-in-australia'],
    'canada' => [VisaSponsorshipJobsCanadaBlogSeeder::class, 'visa-sponsorship-jobs-in-canada'],
]);
