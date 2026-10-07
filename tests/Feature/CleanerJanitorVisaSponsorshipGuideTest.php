<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\CleanerJanitorVisaSponsorshipBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(CleanerJanitorVisaSponsorshipBlogSeeder::class);
    $this->blog = Blog::where('slug', CleanerJanitorVisaSponsorshipBlogSeeder::SLUG)->first();
});

it('publishes with SEO fields inside the column limits', function () {
    expect($this->blog)->not->toBeNull()
        ->and($this->blog->status)->toBe('published')
        ->and($this->blog->featured_image)->toBe('blogs/cleaner-janitor-visa-sponsorship.jpg')
        ->and(strlen($this->blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($this->blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($this->blog->excerpt))->toBeLessThanOrEqual(255)
        ->and(count(array_filter(array_map('trim', explode(',', $this->blog->tags)))))->toBe(8);

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($this->blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }
});

it('has its featured image on disk', function () {
    expect(file_exists(storage_path('app/public/blogs/cleaner-janitor-visa-sponsorship.jpg')))->toBeTrue();
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
        ->not->toContain('add real source')
        ->not->toContain('findability check');
});

it('states the verified visa facts', function (string $claim) {
    expect($this->blog->content)->toContain($claim);
})->with([
    'rqf threshold' => 'Since 22 July 2025',
    'lmia filter' => '"LMIA requested"',
    'noc code' => 'NOC 65310',
    'six percent freeze' => '6 percent or higher',
    'cleaning not exempt' => 'cleaning is not',
    'beoe licence check' => "match the agency's licence number",
]);

it('does not republish unverified quotes, counts or pay', function (string $banned) {
    expect($this->blog->content)->not->toContain($banned);
})->with(['Liverpool', 'GulfTalent', '19.00', '1,200', '2,000 SAR', '61 live']);

it('cross-links to existing guides and the cleaning category', function () {
    expect($this->blog->content)->toContain('/blog/janitor-jobs-in-canada')
        ->toContain('/blog/cleaner-jobs-in-saudi-arabia-for-foreigners')
        ->toContain('/categories/cleaning-facilities');
});

it('creates one listing per country with an official apply URL, no salary and no markup', function (string $position, string $applyUrl, string $country) {
    $job = Job::where('position', 'like', $position.'%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe($applyUrl)
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->location->name)->toBe($country)
        ->and($job->category->slug)->toBe('cleaning-facilities')
        ->and($job->advertiser->name)->toEndWith('(Aggregated)')
        ->and($job->description)->toContain('not by JobGader')
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeFalse();
})->with([
    'canada' => ['Light Duty Cleaner — Canadian', 'https://www.jobbank.gc.ca/jobsearch/jobsearch?searchstring=cleaner', 'Canada'],
    'saudi' => ['Office and Facility Cleaner — Saudi', 'https://beoe.gov.pk/', 'Saudi Arabia'],
]);

it('renders the guide', function () {
    get('/blog/'.CleanerJanitorVisaSponsorshipBlogSeeder::SLUG)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('re-seeds without duplicating the post or the listings', function () {
    $this->seed(CleanerJanitorVisaSponsorshipBlogSeeder::class);

    expect(Blog::where('slug', CleanerJanitorVisaSponsorshipBlogSeeder::SLUG)->count())->toBe(1)
        ->and(Job::where('position', 'like', 'Light Duty Cleaner%')->count())->toBe(1);
});
