<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\HotelHousekeeperJobsUsaVisaSponsorshipBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(HotelHousekeeperJobsUsaVisaSponsorshipBlogSeeder::class);
    $this->blog = Blog::where('slug', HotelHousekeeperJobsUsaVisaSponsorshipBlogSeeder::SLUG)->first();
});

it('publishes with SEO fields inside the column limits', function () {
    expect($this->blog)->not->toBeNull()
        ->and($this->blog->status)->toBe('published')
        ->and($this->blog->featured_image)->toBe('blogs/hotel-housekeeper-jobs-usa-visa-sponsorship.jpg')
        ->and(strlen($this->blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($this->blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($this->blog->excerpt))->toBeLessThanOrEqual(255)
        ->and(count(array_filter(array_map('trim', explode(',', $this->blog->tags)))))->toBe(8);

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($this->blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }
});

it('has its images on disk', function (string $image) {
    expect(file_exists(storage_path("app/public/blogs/{$image}.jpg")))->toBeTrue("{$image}.jpg is missing");
})->with(['hotel-housekeeper-jobs-usa-visa-sponsorship']);

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
        ->not->toContain('apply now');
});

it('states the verified facts', function (string $claim) {
    expect($this->blog->content)->toContain($claim);
})->with(['$17.83', '$17.07', '$37,080', 'took effect on 17 January 2025', 'after 15 September 2026', '$3,120']);

it('cross-links to the sibling guides and the category', function () {
    foreach (['/blog/landscaper-jobs-usa-visa-sponsorship-2026', '/blog/how-to-get-landscaper-job-usa-from-pakistan-2026', '/blog/cleaner-and-janitor-jobs-with-visa-sponsorship', '/categories/hospitality-tourism'] as $link) {
        expect($this->blog->content)->toContain($link);
    }
});

it('creates the listing with the official portal, no salary and no markup', function () {
    $job = Job::where('position', 'like', 'Hotel Housekeeper — US Hotels%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe('https://seasonaljobs.dol.gov/')
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->location->name)->toBe('United States')
        ->and($job->category->slug)->toBe('hospitality-tourism')
        ->and($job->advertiser->name)->toEndWith('(Aggregated)')
        ->and($job->description)->toContain('not by JobGader')
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeFalse();
});

it('renders the guide', function () {
    get('/blog/'.HotelHousekeeperJobsUsaVisaSponsorshipBlogSeeder::SLUG)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('re-seeds without duplicating the post', function () {
    $this->seed(HotelHousekeeperJobsUsaVisaSponsorshipBlogSeeder::class);

    expect(Blog::where('slug', HotelHousekeeperJobsUsaVisaSponsorshipBlogSeeder::SLUG)->count())->toBe(1)
        ->and(Job::count())->toBe(1);
});
