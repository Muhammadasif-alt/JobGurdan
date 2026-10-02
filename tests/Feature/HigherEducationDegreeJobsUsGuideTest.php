<?php

use App\Models\Blog;
use App\Services\StructuredDataService;
use Database\Seeders\AssistantProfessorJobsUsBlogSeeder;
use Database\Seeders\CollegesHiringProfessorsUsBlogSeeder;
use Database\Seeders\FacultyCareersUsBlogSeeder;
use Database\Seeders\HigherEducationDegreeJobsUsBlogSeeder;
use Database\Seeders\ProfessorEmploymentUsBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

const HIGHER_ED_JOBS_SLUG = 'higher-education-degree-jobs-in-the-us';

it('publishes the guide with its own images and SEO fields', function () {
    $this->seed(HigherEducationDegreeJobsUsBlogSeeder::class);

    $blog = Blog::where('slug', HIGHER_ED_JOBS_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/'.HIGHER_ED_JOBS_SLUG.'.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->content)->toContain(HIGHER_ED_JOBS_SLUG.'-campus.jpg')
        ->and($blog->content)->toContain(HIGHER_ED_JOBS_SLUG.'-advising.jpg');

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }

    expect(count(array_filter(array_map('trim', explode(',', $blog->tags)))))->toBe(8);
});

it('carries exactly eight FAQs and eight People Also Search For entries', function () {
    $this->seed(HigherEducationDegreeJobsUsBlogSeeder::class);

    $content = Blog::where('slug', HIGHER_ED_JOBS_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $pasf = substr($content, (int) strpos($content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
});

it('carries the BLS figures the draft left out', function (string $claim) {
    $this->seed(HigherEducationDegreeJobsUsBlogSeeder::class);

    expect(Blog::where('slug', HIGHER_ED_JOBS_SLUG)->value('content'))->toContain($claim);
})->with([
    // Postsecondary education administrators, May 2025 and 2025-35.
    'administrator median' => '$104,590',
    'administrator jobs' => '231,800',
    'administrator openings' => '14,500 openings a year',
    'administrator state universities' => '$107,540',
    'administrator state junior colleges' => '$98,840',
    // The other occupations a higher education degree leads to.
    'instructional coordinator' => '$77,440',
    'librarian' => '$68,270',
    'counsellor and adviser' => '$64,330',
    'archivist group' => '$60,330',
    // The in-sector medians, which are lower than the headline for advisers.
    'public college adviser' => '$58,870',
    'private college adviser' => '$58,720',
    'academic librarian' => '$74,490',
    // The comparison that gives the page its point.
    'teaching median' => '$85,330',
]);

it('does not present administration as a fast-growing field', function () {
    $this->seed(HigherEducationDegreeJobsUsBlogSeeder::class);

    expect(Blog::where('slug', HIGHER_ED_JOBS_SLUG)->value('content'))
        ->toContain('growing far more slowly');
});

it('says there is no separate BLS occupation for academic advisors', function () {
    $this->seed(HigherEducationDegreeJobsUsBlogSeeder::class);

    expect(Blog::where('slug', HIGHER_ED_JOBS_SLUG)->value('content'))
        ->toContain('no separate BLS occupation called "academic advisor"');
});

it('links to no aggregator', function () {
    $this->seed(HigherEducationDegreeJobsUsBlogSeeder::class);

    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', (string) Blog::where('slug', HIGHER_ED_JOBS_SLUG)->value('content'), $matches);

    foreach ($matches[1] as $href) {
        $host = strtolower((string) parse_url($href, PHP_URL_HOST));

        foreach (['indeed', 'ziprecruiter', 'glassdoor', 'simplyhired', 'higheredjobs', 'monster', 'chronicle'] as $label) {
            expect(explode('.', $host))->not->toContain($label);
        }
    }
});

it('renders the page with its long-tail sections', function () {
    $this->seed(HigherEducationDegreeJobsUsBlogSeeder::class);

    get('/blog/'.HIGHER_ED_JOBS_SLUG)
        ->assertOk()
        ->assertSee('Higher Education Degree Jobs in the US')
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('cross-links with every US academic guide in both directions', function (string $seeder, string $slug) {
    $this->seed(HigherEducationDegreeJobsUsBlogSeeder::class);
    $this->seed($seeder);

    expect(Blog::where('slug', HIGHER_ED_JOBS_SLUG)->value('content'))
        ->toContain('/blog/'.$slug)
        ->and(Blog::where('slug', $slug)->value('content'))
        ->toContain('/blog/'.HIGHER_ED_JOBS_SLUG);
})->with([
    'faculty hub' => [FacultyCareersUsBlogSeeder::class, 'faculty-careers-in-the-us'],
    'professor employment' => [ProfessorEmploymentUsBlogSeeder::class, 'professor-employment-in-the-us'],
    'assistant professor' => [AssistantProfessorJobsUsBlogSeeder::class, 'assistant-professor-jobs-in-the-us'],
    'colleges hiring' => [CollegesHiringProfessorsUsBlogSeeder::class, 'colleges-hiring-professors-in-the-us'],
]);
