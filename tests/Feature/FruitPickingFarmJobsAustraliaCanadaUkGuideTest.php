<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\FruitPickingFarmJobsAustraliaCanadaUkBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(FruitPickingFarmJobsAustraliaCanadaUkBlogSeeder::class);
    $this->blog = Blog::where('slug', FruitPickingFarmJobsAustraliaCanadaUkBlogSeeder::SLUG)->first();
});

it('publishes with SEO fields inside the column limits', function () {
    expect($this->blog)->not->toBeNull()
        ->and($this->blog->status)->toBe('published')
        ->and($this->blog->featured_image)->toBe('blogs/fruit-picking-farm-jobs-australia-canada-uk.jpg')
        ->and(strlen($this->blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($this->blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($this->blog->excerpt))->toBeLessThanOrEqual(255)
        ->and(count(array_filter(array_map('trim', explode(',', $this->blog->tags)))))->toBe(8);

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($this->blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }
});

it('has its featured image on disk', function () {
    expect(file_exists(storage_path('app/public/blogs/fruit-picking-farm-jobs-australia-canada-uk.jpg')))->toBeTrue();
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
        ->not->toContain('apply now')
        ->not->toContain('findability check');
});

it('states the verified facts', function (string $claim) {
    expect($this->blog->content)->toContain($claim);
})->with([
    'working holiday' => 'subclasses 417 and 462',
    'palm' => 'nine participating Pacific island countries and Timor-Leste',
    'piecework' => 'piecework has a minimum wage guarantee',
    'sawp' => 'Seasonal Agricultural Worker Program (SAWP)',
    'lmia requested' => '"LMIA requested"',
    'six months' => 'maximum six-month period',
    'operator' => 'approved scheme operator',
]);

it('does not republish unverified claims', function (string $banned) {
    expect($this->blog->content)->not->toContain($banned);
})->with(['Costa', 'Indeed', 'per hour']);

it('cross-links to existing guides and the category', function () {
    expect($this->blog->content)->toContain('/blog/farm-worker-jobs-in-canada')
        ->toContain('/categories/general-labour');
});

it('creates each listing with an official apply URL, no salary and no markup', function (string $position, string $applyUrl, string $country, string $categorySlug) {
    $job = Job::where('position', 'like', $position.'%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe($applyUrl)
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->location->name)->toBe($country)
        ->and($job->category->slug)->toBe($categorySlug)
        ->and($job->advertiser->name)->toEndWith('(Aggregated)')
        ->and($job->description)->toContain('not by JobGader')
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeFalse();
})->with([
    'australia' => ['Fruit Picker and Farm Worker — Australian', 'https://www.workforceaustralia.gov.au/individuals/jobs/search', 'Australia', 'general-labour'],
    'canada' => ['Farm Worker — Canadian', 'https://www.jobbank.gc.ca/jobsearch/jobsearch?fsrc=32&searchstring=farm+worker', 'Canada', 'general-labour'],
    'uk' => ['Seasonal Farm Worker — UK', 'https://www.concordia.org.uk/seasonal-work/information-for-workers/how-to-apply/', 'United Kingdom', 'general-labour'],
]);

it('renders the guide', function () {
    get('/blog/'.FruitPickingFarmJobsAustraliaCanadaUkBlogSeeder::SLUG)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('re-seeds without duplicating the post or the listings', function () {
    $this->seed(FruitPickingFarmJobsAustraliaCanadaUkBlogSeeder::class);

    expect(Blog::where('slug', FruitPickingFarmJobsAustraliaCanadaUkBlogSeeder::SLUG)->count())->toBe(1)
        ->and(Job::count())->toBe(3);
});
