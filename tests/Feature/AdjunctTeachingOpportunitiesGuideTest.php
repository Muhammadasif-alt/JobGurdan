<?php

use App\Models\Blog;
use App\Services\StructuredDataService;
use Database\Seeders\AdjunctFacultyVacanciesUkBlogSeeder;
use Database\Seeders\AdjunctLecturerJobsUkBlogSeeder;
use Database\Seeders\AdjunctProfessorJobsUkBlogSeeder;
use Database\Seeders\AdjunctTeachingJobsUkBlogSeeder;
use Database\Seeders\AdjunctTeachingOpportunitiesBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

const ADJUNCT_HUB_SLUG = 'adjunct-teaching-opportunities';

it('publishes the guide with its own images and SEO fields', function () {
    $this->seed(AdjunctTeachingOpportunitiesBlogSeeder::class);

    $blog = Blog::where('slug', ADJUNCT_HUB_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/'.ADJUNCT_HUB_SLUG.'.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->content)->toContain(ADJUNCT_HUB_SLUG.'-lecture-hall.jpg')
        ->and($blog->content)->toContain(ADJUNCT_HUB_SLUG.'-qualifications.jpg')
        ->and($blog->content)->toContain(ADJUNCT_HUB_SLUG.'-uk-tutorial.jpg');

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }

    expect(count(array_filter(array_map('trim', explode(',', $blog->tags)))))->toBe(8);
});

it('carries exactly eight FAQs and eight People Also Search For entries', function () {
    $this->seed(AdjunctTeachingOpportunitiesBlogSeeder::class);

    $content = Blog::where('slug', ADJUNCT_HUB_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $pasf = substr($content, (int) strpos($content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
});

it('puts the figures the draft left out on the page', function (string $claim) {
    $this->seed(AdjunctTeachingOpportunitiesBlogSeeder::class);

    expect(Blog::where('slug', ADJUNCT_HUB_SLUG)->value('content'))->toContain($claim);
})->with([
    // AAUP Faculty Compensation Survey, the only figure that describes this job.
    'per section pay' => '$4,093',
    // BLS median for all postsecondary teachers, flagged as not the adjunct rate.
    'bls median' => '$85,330',
    'bls year' => 'May 2025',
    // AAUP autumn 2023 workforce shares.
    'part time share' => '48.6 percent',
    'contingent share' => '68.2 percent',
    // The IRS hours-of-service method that decides health coverage.
    'irs multiplier' => '2.25 hours of service for each hour of classroom teaching',
    // The Department of Education PSLF multiplier.
    'pslf multiplier' => '3.35 hours of work for every credit hour taught',
    'pslf threshold' => 'nine credit hours a week clears it',
    // The UK trap this cluster already documents.
    'uk honorary' => 'honorary',
    'uk count' => '3,440',
]);

it('does not present the BLS median as the adjunct rate', function () {
    $this->seed(AdjunctTeachingOpportunitiesBlogSeeder::class);

    expect(Blog::where('slug', ADJUNCT_HUB_SLUG)->value('content'))
        ->toContain('The BLS figure is not your figure');
});

it('links to no aggregator', function () {
    $this->seed(AdjunctTeachingOpportunitiesBlogSeeder::class);

    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', (string) Blog::where('slug', ADJUNCT_HUB_SLUG)->value('content'), $matches);

    foreach ($matches[1] as $href) {
        $host = strtolower((string) parse_url($href, PHP_URL_HOST));

        foreach (['indeed', 'ziprecruiter', 'glassdoor', 'simplyhired', 'higheredjobs', 'monster'] as $label) {
            expect(explode('.', $host))->not->toContain($label);
        }
    }
});

it('renders the page with its long-tail sections', function () {
    $this->seed(AdjunctTeachingOpportunitiesBlogSeeder::class);

    get('/blog/'.ADJUNCT_HUB_SLUG)
        ->assertOk()
        ->assertSee('Adjunct Teaching Opportunities')
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('cross-links with every UK adjunct guide in both directions', function (string $seeder, string $slug) {
    $this->seed(AdjunctTeachingOpportunitiesBlogSeeder::class);
    $this->seed($seeder);

    expect(Blog::where('slug', ADJUNCT_HUB_SLUG)->value('content'))
        ->toContain('/blog/'.$slug)
        ->and(Blog::where('slug', $slug)->value('content'))
        ->toContain('/blog/'.ADJUNCT_HUB_SLUG);
})->with([
    'teaching jobs' => [AdjunctTeachingJobsUkBlogSeeder::class, 'adjunct-teaching-jobs-in-the-uk'],
    'professor jobs' => [AdjunctProfessorJobsUkBlogSeeder::class, 'adjunct-professor-jobs-in-the-uk'],
    'faculty vacancies' => [AdjunctFacultyVacanciesUkBlogSeeder::class, 'adjunct-faculty-vacancies-in-the-uk'],
    'lecturer route' => [AdjunctLecturerJobsUkBlogSeeder::class, 'how-to-become-an-adjunct-lecturer-in-the-uk'],
]);
