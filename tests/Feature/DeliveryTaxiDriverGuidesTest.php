<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\DeliveryCourierJobsNewCountryBlogSeeder;
use Database\Seeders\TaxiUberDriverRequirementsBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

const DELIVERY_COURIER_SLUG = 'delivery-driver-and-courier-jobs-how-to-start-in-a-new-country';
const TAXI_UBER_SLUG = 'taxi-and-uber-driver-requirements-by-country';

dataset('driver guides', [
    'delivery' => [DeliveryCourierJobsNewCountryBlogSeeder::class, DELIVERY_COURIER_SLUG, 'blogs/delivery-driver-courier-jobs-new-country.jpg', ['delivery-driver-courier-jobs-new-country-handover.jpg']],
    'taxi' => [TaxiUberDriverRequirementsBlogSeeder::class, TAXI_UBER_SLUG, 'blogs/taxi-uber-driver-requirements-by-country.jpg', ['taxi-uber-driver-requirements-by-country-hotel.jpg', 'taxi-uber-driver-requirements-by-country-airport.jpg']],
]);

it('publishes each guide with its own images and SEO fields', function (string $seeder, string $slug, string $featured, array $inline) {
    $this->seed($seeder);
    $blog = Blog::where('slug', $slug)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe($featured)
        ->and($blog->content)->not->toContain(basename($featured))
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and(count(array_filter(array_map('trim', explode(',', $blog->tags)))))->toBe(8);

    foreach ($inline as $image) {
        expect($blog->content)->toContain('/public/storage/blogs/'.$image);
    }

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }
})->with('driver guides');

it('carries exactly eight FAQs and eight People Also Search For entries', function (string $seeder, string $slug) {
    $this->seed($seeder);
    $content = Blog::where('slug', $slug)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $pasf = substr($content, (int) strpos($content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
})->with('driver guides');

it('links only internally and drops the brief artifacts', function (string $seeder, string $slug) {
    $this->seed($seeder);
    $content = Blog::where('slug', $slug)->value('content');

    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', $content, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach ($matches[1] as $href) {
        expect($href)->toStartWith('/blog/');
    }

    expect(strtolower($content))->not->toContain('citeturn');
})->with('driver guides');

it('renders each guide', function (string $seeder, string $slug) {
    $this->seed($seeder);

    get('/blog/'.$slug)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
})->with('driver guides');

it('drops the courier links and job-board pay from the delivery guide', function (string $banned) {
    $this->seed(DeliveryCourierJobsNewCountryBlogSeeder::class);

    expect(strtolower(Blog::where('slug', DELIVERY_COURIER_SLUG)->value('content')))->not->toContain($banned);
})->with(['nysaa', 'fbc', 'boom', 'time express', 'inpost', '165', '220', '300', '500+']);

it('states the delivery rules', function (string $claim) {
    $this->seed(DeliveryCourierJobsNewCountryBlogSeeder::class);

    expect(Blog::where('slug', DELIVERY_COURIER_SLUG)->value('content'))->toContain($claim);
})->with([
    'no visit visa work' => 'You cannot work on a visit or tourist visa',
    'mohre permit' => 'work permit from MOHRE',
    'recruitment costs' => 'Article 6 of Federal Decree-Law No. 33 of 2021',
    'motorcycle licence' => 'motorcycle licence',
    'soc 8214' => 'Delivery drivers and couriers (SOC 8214)',
    'wps' => 'Ministerial Resolution No. 340 of 2026',
    'self-employed right to work' => 'still requires each one to have the right to work in the UK',
]);

it('states the taxi and Uber requirements', function (string $claim) {
    $this->seed(TaxiUberDriverRequirementsBlogSeeder::class);

    expect(Blog::where('slug', TAXI_UBER_SLUG)->value('content'))->toContain($claim);
})->with([
    'rta permit' => 'professional driver permit from the Roads and Transport Authority (RTA)',
    'rta tests' => 'English language test and psychological and behavioural assessment',
    'uk issuers' => 'Transport for London (TfL)',
    'soc 8213' => 'Taxi and cab drivers and chauffeurs (SOC 8213)',
    'ncs pay' => '£20,000 for starters and £35,000 for experienced drivers',
    'uber us experience' => 'one year of licensed driving experience in the US',
    'uber under 25' => 'three years if you are under 25',
    'canada varies' => 'requirements vary by city',
    'nsw code' => 'unrestricted Australian driver licence for at least 12 months in the last 4 years',
]);

it('does not carry the wrong SOC code or unverified permit fee', function (string $banned) {
    $this->seed(TaxiUberDriverRequirementsBlogSeeder::class);

    expect(Blog::where('slug', TAXI_UBER_SLUG)->value('content'))->not->toContain($banned);
})->with(['SOC 8215', 'AED 200']);

it('creates listings with official apply URLs, no salary and no markup', function (string $seeder, string $position, string $applyUrl, string $country) {
    $this->seed($seeder);
    $job = Job::where('position', 'like', $position.'%')->first();

    expect($job)->not->toBeNull()
        ->and($job->application_url)->toBe($applyUrl)
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull()
        ->and($job->salary_currency)->toBeNull()
        ->and($job->location->name)->toBe($country)
        ->and($job->category->slug)->toBe('transport-logistics')
        ->and($job->advertiser->name)->toEndWith('(Aggregated)')
        ->and($job->description)->toContain('not by JobGader')
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeFalse();
})->with([
    'uae rider' => [DeliveryCourierJobsNewCountryBlogSeeder::class, 'Delivery Rider and Driver — UAE', 'https://u.ae/en/information-and-services/jobs', 'United Arab Emirates'],
    'uk courier' => [DeliveryCourierJobsNewCountryBlogSeeder::class, 'Courier and Delivery Driver — UK', 'https://www.gov.uk/find-a-job', 'United Kingdom'],
    'uae taxi' => [TaxiUberDriverRequirementsBlogSeeder::class, 'Taxi and Limousine Driver — UAE', 'https://u.ae/en/information-and-services/jobs', 'United Arab Emirates'],
    'uk private hire' => [TaxiUberDriverRequirementsBlogSeeder::class, 'Private Hire Driver — UK', 'https://www.gov.uk/find-a-job', 'United Kingdom'],
]);

it('cross-links the two guides in both directions', function () {
    $this->seed(DeliveryCourierJobsNewCountryBlogSeeder::class);
    $this->seed(TaxiUberDriverRequirementsBlogSeeder::class);

    expect(Blog::where('slug', DELIVERY_COURIER_SLUG)->value('content'))->toContain('/blog/'.TAXI_UBER_SLUG)
        ->and(Blog::where('slug', TAXI_UBER_SLUG)->value('content'))->toContain('/blog/'.DELIVERY_COURIER_SLUG);
});
