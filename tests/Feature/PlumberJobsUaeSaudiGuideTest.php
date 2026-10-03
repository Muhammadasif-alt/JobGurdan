<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\PlumberJobsAustraliaBlogSeeder;
use Database\Seeders\PlumberJobsUaeSaudiBlogSeeder;
use Database\Seeders\SaudiArabiaJobsForeignersBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

const GULF_PLUMBER_SLUG = 'plumber-jobs-in-uae-and-saudi-arabia';

it('publishes the guide with its own images and SEO fields', function () {
    $this->seed(PlumberJobsUaeSaudiBlogSeeder::class);

    $blog = Blog::where('slug', GULF_PLUMBER_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/'.GULF_PLUMBER_SLUG.'.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->content)->toContain(GULF_PLUMBER_SLUG.'-worksite.jpg')
        ->and($blog->content)->toContain(GULF_PLUMBER_SLUG.'-villa.jpg');

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }

    expect(count(array_filter(array_map('trim', explode(',', $blog->tags)))))->toBe(8);
});

it('carries exactly eight FAQs and eight People Also Search For entries', function () {
    $this->seed(PlumberJobsUaeSaudiBlogSeeder::class);

    $content = Blog::where('slug', GULF_PLUMBER_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $pasf = substr($content, (int) strpos($content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
});

it('states the official rules the draft replaced with job-board data', function (string $claim) {
    $this->seed(PlumberJobsUaeSaudiBlogSeeder::class);

    expect(Blog::where('slug', GULF_PLUMBER_SLUG)->value('content'))->toContain($claim);
})->with([
    // UAE Federal Decree-Law 33 of 2021, Article 6.
    'uae law' => 'Federal Decree-Law No. 33 of 2021',
    'recruitment cost ban' => 'may not charge the worker, or collect from the worker, recruitment and employment costs',
    'work permit' => 'MOHRE work permit',
    // The Wage Protection System as it now stands, not the 2022 resolution.
    'wps resolution' => 'Ministerial Resolution No. 340 of 2026',
    'wps share' => '85 per cent',
    'wps superseded' => 'superseded 2022 resolution',
    // The two exceptions that catch trade workers specifically.
    'shift exception' => 'does not apply to workers employed on a shift basis',
    'free zones' => 'Free zone employees are generally not governed by the UAE Labour Law',
    // Saudi Arabia's pre-visa trade exam.
    'saudi exam' => 'Professional Verification',
    'exam before visa' => 'before it issues the work visa',
    'exam at home' => 'You sit it at home, not in Saudi Arabia',
    'programme reach' => '160 labour-exporting countries',
    // Neither country sets a floor for expatriate workers.
    'no minimum wage' => 'Neither country sets a wage floor for a foreign plumber',
    'saudi figure explained' => 'applies to <strong>Saudi nationals</strong>',
]);

it('republishes no aggregator salary figure from the draft', function (string $figure) {
    $this->seed(PlumberJobsUaeSaudiBlogSeeder::class);

    expect(Blog::where('slug', GULF_PLUMBER_SLUG)->value('content'))->not->toContain($figure);
})->with([
    'indeed uae average' => 'AED 2,056',
    'indeed uae senior' => 'AED 2,555',
    'gulftalent median' => 'SAR 2,500',
    'gulftalent low' => 'SAR 2,000',
    'gulftalent high' => 'SAR 5,500',
]);

it('carries none of the draft job-id apply links', function (string $marker) {
    $this->seed(PlumberJobsUaeSaudiBlogSeeder::class);

    $blog = Blog::where('slug', GULF_PLUMBER_SLUG)->first();

    expect($blog->content)->not->toContain($marker);

    foreach (Job::where('position', 'like', 'Plumber —%')->get() as $job) {
        expect($job->description)->not->toContain($marker);
    }
})->with([
    'kerzner job id' => '4167522',
    'marriott job id' => '20A8E14628FFD74FEB4199FD7C7E51E5',
    'hilton job id' => 'HOT0C8HU',
]);

it('links to no aggregator', function () {
    $this->seed(PlumberJobsUaeSaudiBlogSeeder::class);

    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', (string) Blog::where('slug', GULF_PLUMBER_SLUG)->value('content'), $matches);

    foreach ($matches[1] as $href) {
        $host = strtolower((string) parse_url($href, PHP_URL_HOST));

        foreach (['indeed', 'ziprecruiter', 'glassdoor', 'simplyhired', 'monster', 'gulftalent', 'bayt', 'naukrigulf'] as $label) {
            expect(explode('.', $host))->not->toContain($label);
        }
    }
});

it('renders the page with its long-tail sections', function () {
    $this->seed(PlumberJobsUaeSaudiBlogSeeder::class);

    get('/blog/'.GULF_PLUMBER_SLUG)
        ->assertOk()
        ->assertSee('Plumber Jobs in UAE and Saudi Arabia')
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('creates one listing per country, applying through an official service with no salary', function (string $position, string $applyUrl, string $country) {
    $this->seed(PlumberJobsUaeSaudiBlogSeeder::class);

    $job = Job::where('position', 'like', $position.'%')->first();

    expect($job)->not->toBeNull("no listing created for {$position}")
        ->and($job->application_url)->toBe($applyUrl)
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->location->name)->toBe($country)
        ->and($job->advertiser->name)->toContain('(Aggregated)')
        ->and($job->description)->toContain('not by JobGader')
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeFalse();
})->with([
    'uae' => ['Plumber — UAE Facilities Management', 'https://u.ae/en/information-and-services/jobs', 'United Arab Emirates'],
    'saudi' => ['Plumber — Saudi Arabia Contracting', 'https://www.hrsd.gov.sa/en', 'Saudi Arabia'],
]);

it('cross-links with the trade and Saudi guides in both directions', function (string $seeder, string $slug) {
    $this->seed(PlumberJobsUaeSaudiBlogSeeder::class);
    $this->seed($seeder);

    expect(Blog::where('slug', GULF_PLUMBER_SLUG)->value('content'))
        ->toContain('/blog/'.$slug)
        ->and(Blog::where('slug', $slug)->value('content'))
        ->toContain('/blog/'.GULF_PLUMBER_SLUG);
})->with([
    'australia plumber' => [PlumberJobsAustraliaBlogSeeder::class, 'plumber-jobs-in-australia'],
    'saudi hub' => [SaudiArabiaJobsForeignersBlogSeeder::class, 'how-to-get-a-job-in-saudi-arabia-as-a-foreigner'],
]);
