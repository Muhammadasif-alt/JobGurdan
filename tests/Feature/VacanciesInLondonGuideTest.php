<?php

use App\Models\Blog;
use App\Services\StructuredDataService;
use Database\Seeders\JobsInLondonForAmericansBlogSeeder;
use Database\Seeders\UkJobsWithVisaSponsorshipBlogSeeder;
use Database\Seeders\VacanciesInLondonBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

const LONDON_VACANCIES_SLUG = 'vacancies-in-london';

it('publishes the guide with its own images and SEO fields', function () {
    $this->seed(VacanciesInLondonBlogSeeder::class);

    $blog = Blog::where('slug', LONDON_VACANCIES_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/'.LONDON_VACANCIES_SLUG.'.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->content)->toContain(LONDON_VACANCIES_SLUG.'-commuter.jpg')
        ->and($blog->content)->toContain(LONDON_VACANCIES_SLUG.'-jobseeker.jpg');

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }

    expect(count(array_filter(array_map('trim', explode(',', $blog->tags)))))->toBe(8);
});

it('carries exactly eight FAQs and eight People Also Search For entries', function () {
    $this->seed(VacanciesInLondonBlogSeeder::class);

    $content = Blog::where('slug', LONDON_VACANCIES_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $pasf = substr($content, (int) strpos($content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
});

it('carries the official figures the draft left out', function (string $claim) {
    $this->seed(VacanciesInLondonBlogSeeder::class);

    expect(Blog::where('slug', LONDON_VACANCIES_SLUG)->value('content'))->toContain($claim);
})->with([
    // ONS Vacancy Survey, early estimate for June to August 2026.
    'uk vacancies' => '702,000',
    'unemployed per vacancy' => '2.5',
    // ONS regional labour market, May to July 2026. London is the highest region.
    'london unemployment' => '6.8%',
    'uk unemployment' => '4.9%',
    'london employment' => '73.9%',
    'highest region' => 'highest unemployment rate of any UK region',
    // National Minimum Wage rates from 1 April 2026.
    'national living wage' => '£12.71',
    'eighteen to twenty' => '£10.85',
    'under eighteen and apprentice' => '£8.00',
    // Voluntary, and not the same thing as the statutory floor.
    'london living wage' => '£14.80',
    // Statutory holiday and the agency qualifying period.
    'holiday entitlement' => '5.6 weeks',
    'agency equal treatment' => '12 weeks in the same role with the same hirer',
    // Why most advertised London work cannot be sponsored.
    'sponsorship floor' => '£41,700',
]);

it('does not invent a London vacancy count the ONS does not publish', function () {
    $this->seed(VacanciesInLondonBlogSeeder::class);

    expect(Blog::where('slug', LONDON_VACANCIES_SLUG)->value('content'))
        ->toContain('does not publish a separate vacancy count for London');
});

it('links to no aggregator', function () {
    $this->seed(VacanciesInLondonBlogSeeder::class);

    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', (string) Blog::where('slug', LONDON_VACANCIES_SLUG)->value('content'), $matches);

    foreach ($matches[1] as $href) {
        $host = strtolower((string) parse_url($href, PHP_URL_HOST));

        foreach (['indeed', 'ziprecruiter', 'glassdoor', 'simplyhired', 'monster', 'totaljobs', 'reed', 'cv-library'] as $label) {
            expect(explode('.', $host))->not->toContain($label);
        }
    }
});

it('renders the page with its long-tail sections', function () {
    $this->seed(VacanciesInLondonBlogSeeder::class);

    get('/blog/'.LONDON_VACANCIES_SLUG)
        ->assertOk()
        ->assertSee('Vacancies in London')
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('cross-links with its sibling London and sponsorship guides in both directions', function (string $seeder, string $slug) {
    $this->seed(VacanciesInLondonBlogSeeder::class);
    $this->seed($seeder);

    expect(Blog::where('slug', LONDON_VACANCIES_SLUG)->value('content'))
        ->toContain('/blog/'.$slug)
        ->and(Blog::where('slug', $slug)->value('content'))
        ->toContain('/blog/'.LONDON_VACANCIES_SLUG);
})->with([
    'american applicants' => [JobsInLondonForAmericansBlogSeeder::class, 'jobs-in-london-england-for-american-applicants'],
    'uk sponsorship' => [UkJobsWithVisaSponsorshipBlogSeeder::class, 'uk-jobs-with-visa-sponsorship'],
]);
