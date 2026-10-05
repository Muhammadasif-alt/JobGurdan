<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\AcTechnicianJobsDubaiSaudiBlogSeeder;
use Database\Seeders\ElectricianJobsAbroadBlogSeeder;
use Database\Seeders\ElectricianSalaryUaeUkUsaBlogSeeder;
use Database\Seeders\HvacTechnicianJobsUsaCanadaBlogSeeder;
use Database\Seeders\IndustrialVsHouseWiringElectricianBlogSeeder;
use Database\Seeders\MaintenanceTechnicianJobsUsaBlogSeeder;
use Database\Seeders\PlumberJobsUaeSaudiBlogSeeder;
use Database\Seeders\RefrigerationAcCareerBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

dataset('hvac guides', [
    'usa canada' => [HvacTechnicianJobsUsaCanadaBlogSeeder::class, 'hvac-technician-jobs-in-the-usa-and-canada', 'blogs/hvac-technician-jobs-usa-canada.jpg', ['hvac-technician-jobs-usa-canada-rooftop.jpg', 'hvac-technician-jobs-usa-canada-gauges.jpg']],
    'gulf' => [AcTechnicianJobsDubaiSaudiBlogSeeder::class, 'ac-technician-jobs-in-dubai-and-saudi-arabia', 'blogs/ac-technician-jobs-dubai-saudi.jpg', ['ac-technician-jobs-dubai-saudi-gauges.jpg', 'ac-technician-jobs-dubai-saudi-rooftop.jpg']],
    'career' => [RefrigerationAcCareerBlogSeeder::class, 'how-to-start-a-career-in-refrigeration-and-air-conditioning', 'blogs/refrigeration-ac-career.jpg', ['refrigeration-ac-career-rooftop.jpg', 'refrigeration-ac-career-service.jpg']],
]);

it('publishes each guide with its own images and SEO fields', function (string $seeder, string $slug, string $featured, array $inline) {
    $this->seed($seeder);

    $blog = Blog::where('slug', $slug)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe($featured)
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);

    foreach ($inline as $image) {
        expect($blog->content)->toContain($image);
    }

    expect($blog->content)->not->toContain(basename($featured));

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }

    expect(count(array_filter(array_map('trim', explode(',', $blog->tags)))))->toBe(8);
})->with('hvac guides');

it('carries exactly eight FAQs and eight People Also Search For entries', function (string $seeder, string $slug) {
    $this->seed($seeder);

    $content = Blog::where('slug', $slug)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $pasf = substr($content, (int) strpos($content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
})->with('hvac guides');

it('links to no aggregator and no job ID', function (string $seeder, string $slug) {
    $this->seed($seeder);

    $content = (string) Blog::where('slug', $slug)->value('content');

    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', $content, $matches);

    foreach ($matches[1] as $href) {
        expect($href)->toStartWith('/blog/');
    }

    foreach (['indeed', 'glassdoor', 'ziprecruiter', 'gulftalent', 'salaryexpert', 'kerzner.com', 'red seal recruiting'] as $banned) {
        expect(strtolower($content))->not->toContain($banned);
    }
})->with('hvac guides');

it('renders each guide', function (string $seeder, string $slug) {
    $this->seed($seeder);

    get('/blog/'.$slug)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
})->with('hvac guides');

it('uses the current BLS cycle and official Canadian wages', function (string $claim) {
    $this->seed(HvacTechnicianJobsUsaCanadaBlogSeeder::class);

    expect(Blog::where('slug', 'hvac-technician-jobs-in-the-usa-and-canada')->value('content'))->toContain($claim);
})->with([
    'projection period' => '2025 to 2035',
    'growth' => '11 per cent growth',
    'openings' => 'About 40,600 openings a year',
    'median' => '$61,010',
    'top decile' => '$95,210',
    'new york metro' => '$77,990',
    'jackson' => '$52,060',
    'job bank median' => 'C$37.50',
    'compulsory provinces' => 'compulsory in Nova Scotia, New Brunswick, Quebec, Ontario, Manitoba, Saskatchewan and Alberta',
    'ontario code' => '313A',
    'odp card' => 'Ozone Depletion Prevention',
    'apprentice limit' => 'for no more than two years from first registration',
    'texas registration' => 'TDLR technician registration or certification',
]);

it('drops the stale and aggregator figures from the USA and Canada guide', function (string $figure) {
    $this->seed(HvacTechnicianJobsUsaCanadaBlogSeeder::class);

    expect(Blog::where('slug', 'hvac-technician-jobs-in-the-usa-and-canada')->value('content'))->not->toContain($figure);
})->with([
    'old openings' => '40,100',
    'old top decile' => '91,020',
    'wrong manhattan' => '84,100',
    'wrong jackson' => '47,560',
    'red seal recruiting average' => '44.41',
    'indeed canada median' => '36.73',
    'unsourced shortage' => '80,000',
]);

it('states the Gulf rules instead of job-board pay', function (string $claim) {
    $this->seed(AcTechnicianJobsDubaiSaudiBlogSeeder::class);

    expect(Blog::where('slug', 'ac-technician-jobs-in-dubai-and-saudi-arabia')->value('content'))->toContain($claim);
})->with([
    'no minimum wage' => 'Neither country sets a wage floor for a foreign AC technician',
    'recruitment costs' => 'Article 6 of Federal Decree-Law No. 33 of 2021',
    'professional verification' => 'Professional Verification',
    'first five trades' => 'among the first five trades',
    'uae midday break' => 'from 12:30pm to 3pm',
    'midday fine' => 'AED 5,000 per worker',
    'wps' => 'Ministerial Resolution No. 340 of 2026',
    'shift exception' => 'does not apply to workers on shifts',
]);

it('drops the aggregator pay figures from the Gulf guide', function (string $figure) {
    $this->seed(AcTechnicianJobsDubaiSaudiBlogSeeder::class);

    expect(Blog::where('slug', 'ac-technician-jobs-in-dubai-and-saudi-arabia')->value('content'))->not->toContain($figure);
})->with([
    'indeed range' => '6,500',
    'salaryexpert uae' => '13,770',
    'salaryexpert saudi' => '10,300',
    'district median' => '3,398',
]);

it('replaces the job-board specialisation table with BLS industry medians', function (string $claim) {
    $this->seed(RefrigerationAcCareerBlogSeeder::class);

    expect(Blog::where('slug', 'how-to-start-a-career-in-refrigeration-and-air-conditioning')->value('content'))->toContain($claim);
})->with([
    'contractors' => '$60,070',
    'food manufacturing' => '$64,760',
    'warehousing' => '$66,150',
    'local government' => '$79,360',
    'epa types' => 'Type III</strong> &mdash; low-pressure equipment',
    'no expiry' => 'Section 608 credentials do not expire',
    'reta voluntary' => 'They are voluntary',
    'texas contractor' => '48 months of practical experience',
    'psm threshold' => '10,000 pounds of anhydrous ammonia',
]);

it('creates listings with official apply URLs, no salary and no markup', function (string $seeder, string $position, string $applyUrl, string $country) {
    $this->seed($seeder);

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
    'us hvac' => [HvacTechnicianJobsUsaCanadaBlogSeeder::class, 'HVAC Technician — US', 'https://www.usa.gov/job-search', 'United States'],
    'canada' => [HvacTechnicianJobsUsaCanadaBlogSeeder::class, 'Refrigeration and Air Conditioning Mechanic — Canadian', 'https://www.jobbank.gc.ca/jobsearch', 'Canada'],
    'uae' => [AcTechnicianJobsDubaiSaudiBlogSeeder::class, 'AC Technician — UAE', 'https://u.ae/en/information-and-services/jobs', 'United Arab Emirates'],
    'saudi' => [AcTechnicianJobsDubaiSaudiBlogSeeder::class, 'AC and Refrigeration Technician — Saudi', 'https://www.hrsd.gov.sa/en', 'Saudi Arabia'],
    'us refrigeration' => [RefrigerationAcCareerBlogSeeder::class, 'Refrigeration Technician — US', 'https://www.usa.gov/job-search', 'United States'],
]);

it('cross-links the cluster in both directions', function (string $fromSeeder, string $fromSlug, string $toSeeder, string $toSlug) {
    $this->seed($fromSeeder);
    $this->seed($toSeeder);

    expect(Blog::where('slug', $fromSlug)->value('content'))->toContain('/blog/'.$toSlug)
        ->and(Blog::where('slug', $toSlug)->value('content'))->toContain('/blog/'.$fromSlug);
})->with([
    'usa canada and gulf' => [HvacTechnicianJobsUsaCanadaBlogSeeder::class, 'hvac-technician-jobs-in-the-usa-and-canada', AcTechnicianJobsDubaiSaudiBlogSeeder::class, 'ac-technician-jobs-in-dubai-and-saudi-arabia'],
    'usa canada and career' => [HvacTechnicianJobsUsaCanadaBlogSeeder::class, 'hvac-technician-jobs-in-the-usa-and-canada', RefrigerationAcCareerBlogSeeder::class, 'how-to-start-a-career-in-refrigeration-and-air-conditioning'],
    'gulf and career' => [AcTechnicianJobsDubaiSaudiBlogSeeder::class, 'ac-technician-jobs-in-dubai-and-saudi-arabia', RefrigerationAcCareerBlogSeeder::class, 'how-to-start-a-career-in-refrigeration-and-air-conditioning'],
    'maintenance and usa canada' => [MaintenanceTechnicianJobsUsaBlogSeeder::class, 'maintenance-technician-jobs-in-usa', HvacTechnicianJobsUsaCanadaBlogSeeder::class, 'hvac-technician-jobs-in-the-usa-and-canada'],
    'maintenance and career' => [MaintenanceTechnicianJobsUsaBlogSeeder::class, 'maintenance-technician-jobs-in-usa', RefrigerationAcCareerBlogSeeder::class, 'how-to-start-a-career-in-refrigeration-and-air-conditioning'],
    'electrician abroad and usa canada' => [ElectricianJobsAbroadBlogSeeder::class, 'electrician-jobs-abroad-with-visa-sponsorship', HvacTechnicianJobsUsaCanadaBlogSeeder::class, 'hvac-technician-jobs-in-the-usa-and-canada'],
    'gulf plumber and gulf' => [PlumberJobsUaeSaudiBlogSeeder::class, 'plumber-jobs-in-uae-and-saudi-arabia', AcTechnicianJobsDubaiSaudiBlogSeeder::class, 'ac-technician-jobs-in-dubai-and-saudi-arabia'],
    'electrician salary and gulf' => [ElectricianSalaryUaeUkUsaBlogSeeder::class, 'electrician-salary-in-the-uae-uk-and-usa', AcTechnicianJobsDubaiSaudiBlogSeeder::class, 'ac-technician-jobs-in-dubai-and-saudi-arabia'],
    'industrial electrician and career' => [IndustrialVsHouseWiringElectricianBlogSeeder::class, 'industrial-vs-house-wiring-electrician-which-pays-more', RefrigerationAcCareerBlogSeeder::class, 'how-to-start-a-career-in-refrigeration-and-air-conditioning'],
]);
