<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\FarmWorkerJobsUsaVisaSponsorshipBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(FarmWorkerJobsUsaVisaSponsorshipBlogSeeder::class);
    $this->blog = Blog::where('slug', FarmWorkerJobsUsaVisaSponsorshipBlogSeeder::SLUG)->first();
});

it('publishes with SEO fields inside the column limits', function () {
    expect($this->blog)->not->toBeNull()
        ->and($this->blog->status)->toBe('published')
        ->and($this->blog->featured_image)->toBe('blogs/farm-worker-jobs-usa-visa-sponsorship.jpg')
        ->and(strlen($this->blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($this->blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($this->blog->excerpt))->toBeLessThanOrEqual(255)
        ->and(count(array_filter(array_map('trim', explode(',', $this->blog->tags)))))->toBe(8);

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($this->blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }
});

it('has its featured image on disk', function () {
    expect(file_exists(storage_path('app/public/blogs/farm-worker-jobs-usa-visa-sponsorship.jpg')))->toBeTrue();
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
        ->not->toContain('september 2026');
});

it('states the verified rules', function (string $claim) {
    expect($this->blog->content)->toContain($claim);
})->with([
    'h2a is agricultural' => 'temporary or seasonal agricultural employment',
    'h2b is not' => 'temporary <em>non-agricultural</em> work',
    'country lists removed' => 'took effect on 17 January 2025',
    'free housing' => 'housing at no cost',
    'half contract' => 'once you complete half the contract',
    'three fourths' => 'at least 75% of the workdays',
]);

it('cross-links to the salary guide, the Canada guide and the category', function () {
    expect($this->blog->content)->toContain('/blog/farm-worker-salary-usa-2026')
        ->toContain('/blog/farm-worker-jobs-in-canada')
        ->toContain('/categories/general-labour');
});

it('creates the listing with the official portal, no salary and no markup', function () {
    $job = Job::where('position', 'like', 'Farm Worker — US Farms%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe('https://seasonaljobs.dol.gov/')
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->location->name)->toBe('United States')
        ->and($job->category->slug)->toBe('general-labour')
        ->and($job->advertiser->name)->toEndWith('(Aggregated)')
        ->and($job->description)->toContain('not by JobGader')
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeFalse();
});

it('renders the guide', function () {
    get('/blog/'.FarmWorkerJobsUsaVisaSponsorshipBlogSeeder::SLUG)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('re-seeds without duplicating the post or the listing', function () {
    $this->seed(FarmWorkerJobsUsaVisaSponsorshipBlogSeeder::class);

    expect(Blog::where('slug', FarmWorkerJobsUsaVisaSponsorshipBlogSeeder::SLUG)->count())->toBe(1)
        ->and(Job::count())->toBe(1);
});
