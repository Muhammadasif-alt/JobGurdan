<?php

use App\Models\Blog;
use App\Services\StructuredDataService;
use Database\Seeders\AdjunctTeachingOpportunitiesBlogSeeder;
use Database\Seeders\FacultyCareersUsBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

const US_FACULTY_SLUG = 'faculty-careers-in-the-us';

it('publishes the guide with its own images and SEO fields', function () {
    $this->seed(FacultyCareersUsBlogSeeder::class);

    $blog = Blog::where('slug', US_FACULTY_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/'.US_FACULTY_SLUG.'.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->content)->toContain(US_FACULTY_SLUG.'-campus.jpg')
        ->and($blog->content)->toContain(US_FACULTY_SLUG.'-application.jpg');

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }

    expect(count(array_filter(array_map('trim', explode(',', $blog->tags)))))->toBe(8);
});

it('carries exactly eight FAQs and eight People Also Search For entries', function () {
    $this->seed(FacultyCareersUsBlogSeeder::class);

    $content = Blog::where('slug', US_FACULTY_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $pasf = substr($content, (int) strpos($content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
});

it('carries the federal and AAUP figures the draft left out', function (string $claim) {
    $this->seed(FacultyCareersUsBlogSeeder::class);

    expect(Blog::where('slug', US_FACULTY_SLUG)->value('content'))->toContain($claim);
})->with([
    // BLS Occupational Outlook Handbook, postsecondary teachers.
    'bls median' => '$85,330',
    'bls year' => 'May 2025',
    'lowest tenth' => '$49,540',
    'highest tenth' => '$203,580',
    'employment' => '1,378,200',
    'projection' => '+7 per cent, +98,200 jobs',
    'annual openings' => '103,300',
    // BLS medians by institution type, which move the number more than discipline.
    'state university median' => '$96,120',
    'state junior college median' => '$68,160',
    // AAUP workforce shares: most faculty are not on a tenure track.
    'part time share' => '48.6 per cent',
    'contingent share' => '68.2 per cent',
    // AAUP per-section pay, which the occupational median does not describe.
    'per section pay' => '$4,093',
    // The AAUP probationary standard most institutions follow.
    'tenure clock' => 'seven years',
    // The two federal multipliers that decide benefits.
    'irs multiplier' => '2.25 hours of service for each hour of classroom teaching',
    'pslf multiplier' => '3.35 hours of work for every credit hour taught',
]);

it('does not present the BLS median as the adjunct rate', function () {
    $this->seed(FacultyCareersUsBlogSeeder::class);

    expect(Blog::where('slug', US_FACULTY_SLUG)->value('content'))
        ->toContain('None of these describe adjunct pay');
});

it('links to no aggregator', function () {
    $this->seed(FacultyCareersUsBlogSeeder::class);

    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', (string) Blog::where('slug', US_FACULTY_SLUG)->value('content'), $matches);

    foreach ($matches[1] as $href) {
        $host = strtolower((string) parse_url($href, PHP_URL_HOST));

        foreach (['indeed', 'ziprecruiter', 'glassdoor', 'simplyhired', 'higheredjobs', 'monster', 'chronicle'] as $label) {
            expect(explode('.', $host))->not->toContain($label);
        }
    }
});

it('renders the page with its long-tail sections', function () {
    $this->seed(FacultyCareersUsBlogSeeder::class);

    get('/blog/'.US_FACULTY_SLUG)
        ->assertOk()
        ->assertSee('Faculty Careers in the US')
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('cross-links with the adjunct hub in both directions', function () {
    $this->seed(FacultyCareersUsBlogSeeder::class);
    $this->seed(AdjunctTeachingOpportunitiesBlogSeeder::class);

    expect(Blog::where('slug', US_FACULTY_SLUG)->value('content'))
        ->toContain('/blog/adjunct-teaching-opportunities')
        ->and(Blog::where('slug', 'adjunct-teaching-opportunities')->value('content'))
        ->toContain('/blog/'.US_FACULTY_SLUG);
});
