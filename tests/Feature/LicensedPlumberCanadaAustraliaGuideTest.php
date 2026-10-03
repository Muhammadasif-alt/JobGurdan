<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\LicensedPlumberCanadaAustraliaBlogSeeder;
use Database\Seeders\PlumberJobsAustraliaBlogSeeder;
use Database\Seeders\PlumberJobsUaeSaudiBlogSeeder;
use Database\Seeders\PlumberSalaryUkUsaBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

const PLUMBER_LICENCE_SLUG = 'how-to-become-a-licensed-plumber-in-canada-or-australia';

it('publishes the guide with its own images and SEO fields', function () {
    $this->seed(LicensedPlumberCanadaAustraliaBlogSeeder::class);

    $blog = Blog::where('slug', PLUMBER_LICENCE_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/licensed-plumber-canada-australia.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->content)->toContain('licensed-plumber-canada-australia-sink.jpg')
        ->and($blog->content)->toContain('licensed-plumber-canada-australia-van.jpg');

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }

    expect(count(array_filter(array_map('trim', explode(',', $blog->tags)))))->toBe(8);
});

it('carries exactly eight FAQs and eight People Also Search For entries', function () {
    $this->seed(LicensedPlumberCanadaAustraliaBlogSeeder::class);

    $content = Blog::where('slug', PLUMBER_LICENCE_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $pasf = substr($content, (int) strpos($content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
});

it('carries the verified Canadian certification facts', function (string $claim) {
    $this->seed(LicensedPlumberCanadaAustraliaBlogSeeder::class);

    expect(Blog::where('slug', PLUMBER_LICENCE_SLUG)->value('content'))->toContain($claim);
})->with([
    // NOC 2021 version 1.0.
    'noc code' => 'NOC 72300',
    'teer level' => 'TEER 2',
    // The compulsory and voluntary split, from the federal profile.
    'compulsory list' => 'Nova Scotia, Prince Edward Island, New Brunswick, Quebec, Ontario, Saskatchewan, Alberta',
    'bc exception' => 'plumber is not on it',
    // Apprenticeship hours from the provincial authorities.
    'ontario hours' => '9,000 hours',
    'ontario split' => '8,280 hours of on-the-job experience and 720 hours',
    'saskatchewan hours' => '7,200 trade hours',
    'alberta periods' => '1,560 hours of work experience and eight weeks',
    // Red Seal.
    'red seal questions' => '125 questions',
    'pass mark' => '70 per cent',
    // Federal Skilled Trades Program.
    'fstp hours' => '3,120 hours',
    'fstp language' => 'CLB 5 for speaking and listening and CLB 4 for reading and writing',
    'fstp education' => 'No education requirement',
]);

it('corrects the 600-point claim rather than repeating it', function (string $claim) {
    $this->seed(LicensedPlumberCanadaAustraliaBlogSeeder::class);

    expect(Blog::where('slug', PLUMBER_LICENCE_SLUG)->value('content'))->toContain($claim);
})->with([
    'states it is false' => 'That sentence welds together two unrelated mechanisms, and the result is false',
    'categories award nothing' => 'Category-based selection awards no points at all',
    'nomination only' => 'The 600 points come only from a provincial or territorial nomination',
    'arranged employment removed' => 'removed on 25 March 2025',
    'offer still qualifies' => 'it still counts for that purpose',
]);

it('keeps the Australian qualification and licence apart', function (string $claim) {
    $this->seed(LicensedPlumberCanadaAustraliaBlogSeeder::class);

    expect(Blog::where('slug', PLUMBER_LICENCE_SLUG)->value('content'))->toContain($claim);
})->with([
    'certificate' => 'Certificate III in Plumbing',
    'white card' => 'Construction Induction Card',
    'licence is separate' => 'The Certificate III in Plumbing</strong> is the trade qualification',
    'amr scheme' => 'Automatic Mutual Recognition',
    'queensland out' => 'Queensland does not participate',
    'classes excluded' => 'Specific plumbing classes are carved out',
    'assessment is not a licence' => 'A skills assessment is not the same as a licence',
]);

it('links to no aggregator', function () {
    $this->seed(LicensedPlumberCanadaAustraliaBlogSeeder::class);

    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', (string) Blog::where('slug', PLUMBER_LICENCE_SLUG)->value('content'), $matches);

    foreach ($matches[1] as $href) {
        $host = strtolower((string) parse_url($href, PHP_URL_HOST));

        foreach (['indeed', 'ziprecruiter', 'glassdoor', 'simplyhired', 'monster', 'seek', 'jobbank'] as $label) {
            expect(explode('.', $host))->not->toContain($label);
        }
    }
});

it('renders the page with its long-tail sections', function () {
    $this->seed(LicensedPlumberCanadaAustraliaBlogSeeder::class);

    get('/blog/'.PLUMBER_LICENCE_SLUG)
        ->assertOk()
        ->assertSee('How to Become a Licensed Plumber in Canada or Australia')
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('creates one listing per country with no salary and no markup', function (string $position, string $applyUrl, string $country) {
    $this->seed(LicensedPlumberCanadaAustraliaBlogSeeder::class);

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
    'canada' => ['Plumber and Plumbing Apprentice — Canadian', 'https://www.jobbank.gc.ca/jobsearch', 'Canada'],
    'australia' => ['Licensed Plumber and Apprentice — Australian', 'https://www.workforceaustralia.gov.au/individuals/jobs/search', 'Australia'],
]);

it('cross-links with the rest of the plumbing cluster in both directions', function (string $seeder, string $slug) {
    $this->seed(LicensedPlumberCanadaAustraliaBlogSeeder::class);
    $this->seed($seeder);

    expect(Blog::where('slug', PLUMBER_LICENCE_SLUG)->value('content'))
        ->toContain('/blog/'.$slug)
        ->and(Blog::where('slug', $slug)->value('content'))
        ->toContain('/blog/'.PLUMBER_LICENCE_SLUG);
})->with([
    'gulf plumber' => [PlumberJobsUaeSaudiBlogSeeder::class, 'plumber-jobs-in-uae-and-saudi-arabia'],
    'plumber pay' => [PlumberSalaryUkUsaBlogSeeder::class, 'plumber-salary-in-the-uk-and-usa'],
    'australia plumber' => [PlumberJobsAustraliaBlogSeeder::class, 'plumber-jobs-in-australia'],
]);
