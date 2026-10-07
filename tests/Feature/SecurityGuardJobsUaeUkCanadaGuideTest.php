<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\SecurityGuardJobsUaeUkCanadaBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(SecurityGuardJobsUaeUkCanadaBlogSeeder::class);
    $this->blog = Blog::where('slug', SecurityGuardJobsUaeUkCanadaBlogSeeder::SLUG)->first();
});

it('publishes with SEO fields inside the column limits', function () {
    expect($this->blog)->not->toBeNull()
        ->and($this->blog->status)->toBe('published')
        ->and($this->blog->featured_image)->toBe('blogs/security-guard-jobs-uae-uk-canada.jpg')
        ->and(strlen($this->blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($this->blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($this->blog->excerpt))->toBeLessThanOrEqual(255)
        ->and(count(array_filter(array_map('trim', explode(',', $this->blog->tags)))))->toBe(8);

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($this->blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }
});

it('has its featured image on disk', function () {
    expect(file_exists(storage_path('app/public/blogs/security-guard-jobs-uae-uk-canada.jpg')))->toBeTrue();
});

it('carries exactly eight FAQs and eight People Also Search For entries', function () {
    expect(app(StructuredDataService::class)->faqsFromHtml($this->blog->content))->toHaveCount(8);

    $pasf = substr($this->blog->content, (int) strpos($this->blog->content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
});

it('links only to our own pages and drops the brief artifacts', function () {
    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', $this->blog->content, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach ($matches[1] as $href) {
        expect($href)->toMatch('#^/(blog|categories)/#');
    }

    expect(strtolower($this->blog->content))->not->toContain('indeed')
        ->not->toContain('add real')
        ->not->toContain('findability check');
});

it('states the verified licence facts', function (string $claim) {
    expect($this->blog->content)->toContain($claim);
})->with([
    'sira covers dubai only' => 'it does not license the rest of the UAE',
    'article 6' => 'Article 6 of Federal Decree-Law No. 33 of 2021',
    'sia age and qualification' => 'hold an SIA-recognised licence-linked qualification',
    'sia right to work' => 'checks it against Home Office records',
    'sia in-house' => 'working in-house',
    'ontario eligibility' => 'legally entitled to work in Canada',
    'ontario course' => '40-hour security guard training course',
    'visitor visa' => 'A visitor visa does not allow you to work',
]);

it('does not republish unverified fees, tests or dates', function (string $banned) {
    expect($this->blog->content)->not->toContain($banned);
})->with(['AED 1,000', 'bleep', '18 February 2024', '$80', 'second birthday']);

it('cross-links to existing guides and the security category', function () {
    expect($this->blog->content)->toContain('/blog/security-guard-jobs-in-uae')
        ->toContain('/blog/jobs-in-canada-for-foreign-workers')
        ->toContain('/categories/security');
});

it('creates one listing per country with an official apply URL, no salary and no markup', function (string $position, string $applyUrl, string $country) {
    $job = Job::where('position', 'like', $position.'%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe($applyUrl)
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->location->name)->toBe($country)
        ->and($job->category->slug)->toBe('security')
        ->and($job->advertiser->name)->toEndWith('(Aggregated)')
        ->and($job->description)->toContain('not by JobGader')
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeFalse();
})->with([
    'uae' => ['Security Guard — UAE', 'https://u.ae/en/information-and-services/jobs', 'United Arab Emirates'],
    'uk' => ['Security Guard — UK', 'https://www.gov.uk/guidance/apply-for-an-sia-licence', 'United Kingdom'],
    'canada' => ['Security Guard — Canadian', 'https://www.jobbank.gc.ca/jobsearch/jobsearch?searchstring=security+guard', 'Canada'],
]);

it('renders the guide', function () {
    get('/blog/'.SecurityGuardJobsUaeUkCanadaBlogSeeder::SLUG)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('re-seeds without duplicating the post or the listings', function () {
    $this->seed(SecurityGuardJobsUaeUkCanadaBlogSeeder::class);

    expect(Blog::where('slug', SecurityGuardJobsUaeUkCanadaBlogSeeder::SLUG)->count())->toBe(1)
        ->and(Job::where('position', 'like', 'Security Guard — %')->count())->toBe(3);
});
