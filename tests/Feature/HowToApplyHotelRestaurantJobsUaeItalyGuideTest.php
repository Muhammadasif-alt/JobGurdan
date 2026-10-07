<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\HowToApplyHotelRestaurantJobsUaeItalyBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(HowToApplyHotelRestaurantJobsUaeItalyBlogSeeder::class);
    $this->blog = Blog::where('slug', HowToApplyHotelRestaurantJobsUaeItalyBlogSeeder::SLUG)->first();
});

it('publishes with its images and SEO fields', function () {
    expect($this->blog)->not->toBeNull()
        ->and($this->blog->status)->toBe('published')
        ->and($this->blog->featured_image)->toBe('blogs/how-to-apply-hotel-restaurant-jobs-uae-italy.jpg')
        ->and($this->blog->content)->toContain('/public/storage/blogs/how-to-apply-hotel-restaurant-jobs-uae-italy-front-desk.jpg')
        ->and($this->blog->content)->not->toContain('blogs/how-to-apply-hotel-restaurant-jobs-uae-italy.jpg')
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
})->with(['how-to-apply-hotel-restaurant-jobs-uae-italy', 'how-to-apply-hotel-restaurant-jobs-uae-italy-front-desk']);

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
        ->not->toContain('sources checked');
});

it('states the verified rules', function (string $claim) {
    expect($this->blog->content)->toContain($claim);
})->with([
    'employer files nulla osta' => 'the employer applies, not you',
    'no visit visa work' => 'cannot work on a visit or tourist visa',
    'recruitment costs' => 'Article 6 of Federal Decree-Law No. 33 of 2021',
    'shift exception' => 'does not apply to workers on shift patterns',
]);

it('cross-links to the rules guide and the hospitality category', function () {
    expect($this->blog->content)->toContain('/blog/hotel-and-restaurant-jobs-in-the-uae-and-italy')
        ->toContain('/blog/receptionist-jobs-in-uae')
        ->toContain('/categories/hospitality-tourism');
});

it('creates one listing per employer portal with an official apply URL, no salary and no markup', function (string $position, string $applyUrl, string $country) {
    $job = Job::where('position', 'like', $position.'%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe($applyUrl)
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->location->name)->toBe($country)
        ->and($job->category->slug)->toBe('hospitality-tourism')
        ->and($job->advertiser->name)->toEndWith('(Aggregated)')
        ->and($job->description)->toContain('not by JobGader')
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeFalse();
})->with([
    'jumeirah' => ['Hotel and Guest Service Staff — Jumeirah', 'https://www.jumeirah.com/en/careers', 'United Arab Emirates'],
    'rotana' => ['Hotel Staff — Rotana', 'https://www.rotanacareers.com/', 'United Arab Emirates'],
    'accor' => ['Hotel and Restaurant Staff — Accor', 'https://careers.accor.com/global/en/italy', 'Italy'],
    'marriott' => ['Hotel Staff — Marriott', 'https://careers.marriott.com/', 'Italy'],
]);

it('renders the guide', function () {
    get('/blog/'.HowToApplyHotelRestaurantJobsUaeItalyBlogSeeder::SLUG)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('re-seeds without duplicating the post or the listings', function () {
    $this->seed(HowToApplyHotelRestaurantJobsUaeItalyBlogSeeder::class);

    expect(Blog::where('slug', HowToApplyHotelRestaurantJobsUaeItalyBlogSeeder::SLUG)->count())->toBe(1)
        ->and(Job::where('position', 'like', 'Hotel Staff — Rotana%')->count())->toBe(1);
});
