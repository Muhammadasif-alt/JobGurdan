<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\HotelRestaurantJobsUaeItalyBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(HotelRestaurantJobsUaeItalyBlogSeeder::class);
    $this->blog = Blog::where('slug', HotelRestaurantJobsUaeItalyBlogSeeder::SLUG)->first();
});

it('publishes with its images and SEO fields', function () {
    expect($this->blog)->not->toBeNull()
        ->and($this->blog->status)->toBe('published')
        ->and($this->blog->featured_image)->toBe('blogs/hotel-restaurant-jobs-uae-italy.jpg')
        ->and($this->blog->content)->toContain('/public/storage/blogs/hotel-restaurant-jobs-uae-italy-dining.jpg')
        ->and($this->blog->content)->toContain('/public/storage/blogs/hotel-restaurant-jobs-uae-italy-terrace.jpg')
        ->and($this->blog->content)->not->toContain('blogs/hotel-restaurant-jobs-uae-italy.jpg')
        ->and(strlen($this->blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($this->blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($this->blog->excerpt))->toBeLessThanOrEqual(255)
        ->and(count(array_filter(array_map('trim', explode(',', $this->blog->tags)))))->toBe(8);

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($this->blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }

    foreach (['hotel-restaurant-jobs-uae-italy', 'hotel-restaurant-jobs-uae-italy-dining', 'hotel-restaurant-jobs-uae-italy-terrace'] as $image) {
        expect(file_exists(storage_path("app/public/blogs/{$image}.jpg")))->toBeTrue("{$image}.jpg is missing");
    }
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
        ->not->toContain('add real link')
        ->not->toContain('findability check')
        ->not->toContain('citeturn');
});

it('states the verified Italian and UAE rules', function (string $claim) {
    expect($this->blog->content)->toContain($claim);
})->with([
    'employer files nulla osta' => 'The employer applies, not you.',
    'seasonal quota' => '89,000 seasonal entries in agriculture and tourism',
    'tourism click day' => '9 February 2027',
    'prefill window' => 'Pre-filling runs from 23 October to 7 December 2026',
    'ali portal' => "Interior Ministry's ALI portal",
    'joint circular' => 'joint circular of 1 October 2026',
    'no visit visa work' => 'cannot work on a visit or tourist visa',
    'recruitment costs' => 'Article 6 of Federal Decree-Law No. 33 of 2021',
    'wps' => 'Ministerial Resolution No. 340 of 2026',
    'shift exception' => 'does not apply to workers on shift patterns',
    'jumeirah warning' => 'does not require fees for processing applications or employment visas',
]);

it('does not carry the unverified circular number, dead page or language rule', function (string $banned) {
    expect($this->blog->content)->not->toContain($banned);
})->with(['7185', 'esteri.it', 'Hapimag', 'Tuscany', 'Abruzzo', 'core requirement']);

it('cross-links to existing guides and the hospitality category', function () {
    expect($this->blog->content)->toContain('/blog/hotel-jobs-in-usa-for-foreigners')
        ->toContain('/blog/receptionist-jobs-in-uae')
        ->toContain('/categories/hospitality-tourism');
});

it('creates one listing per country with an official apply URL, no salary and no markup', function (string $position, string $applyUrl, string $country) {
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
    'uae' => ['Hotel and Restaurant Staff — UAE', 'https://u.ae/en/information-and-services/jobs', 'United Arab Emirates'],
    'italy' => ['Seasonal Waiter and Kitchen Staff — Italian', 'https://www.cliclavoro.gov.it/', 'Italy'],
]);

it('renders the guide', function () {
    get('/blog/'.HotelRestaurantJobsUaeItalyBlogSeeder::SLUG)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
});

it('re-seeds without duplicating the post or the listings', function () {
    $this->seed(HotelRestaurantJobsUaeItalyBlogSeeder::class);

    expect(Blog::where('slug', HotelRestaurantJobsUaeItalyBlogSeeder::SLUG)->count())->toBe(1)
        ->and(Job::where('position', 'like', 'Hotel and Restaurant Staff%')->count())->toBe(1);
});
