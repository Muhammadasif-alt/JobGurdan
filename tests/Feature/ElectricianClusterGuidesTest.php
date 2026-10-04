<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\ElectricianJobsAbroadBlogSeeder;
use Database\Seeders\ElectricianJobsUkBlogSeeder;
use Database\Seeders\ElectricianSalaryUaeUkUsaBlogSeeder;
use Database\Seeders\IndustrialVsHouseWiringElectricianBlogSeeder;
use Database\Seeders\LicensedPlumberCanadaAustraliaBlogSeeder;
use Database\Seeders\PlumberSalaryUkUsaBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

dataset('electrician guides', [
    'abroad' => [ElectricianJobsAbroadBlogSeeder::class, 'electrician-jobs-abroad-with-visa-sponsorship', 'blogs/electrician-jobs-abroad-visa-sponsorship.jpg', ['electrician-jobs-abroad-visa-sponsorship-dubai.jpg', 'electrician-jobs-abroad-visa-sponsorship-sydney.jpg']],
    'salary' => [ElectricianSalaryUaeUkUsaBlogSeeder::class, 'electrician-salary-in-the-uae-uk-and-usa', 'blogs/electrician-salary-uae-uk-usa.jpg', ['electrician-salary-uae-uk-usa-panorama.jpg']],
    'industrial' => [IndustrialVsHouseWiringElectricianBlogSeeder::class, 'industrial-vs-house-wiring-electrician-which-pays-more', 'blogs/industrial-vs-house-wiring-electrician.jpg', ['industrial-vs-house-wiring-electrician-refinery.jpg', 'industrial-vs-house-wiring-electrician-panels.jpg']],
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

    foreach (['tags', 'meta_title', 'meta_description'] as $field) {
        expect(mb_check_encoding($blog->$field, 'ASCII'))->toBeTrue("{$field} carries non-ASCII characters");
    }

    expect(count(array_filter(array_map('trim', explode(',', $blog->tags)))))->toBe(8);
})->with('electrician guides');

it('carries exactly eight FAQs and eight People Also Search For entries', function (string $seeder, string $slug) {
    $this->seed($seeder);

    $content = Blog::where('slug', $slug)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $pasf = substr($content, (int) strpos($content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
})->with('electrician guides');

it('links to no aggregator and no job ID', function (string $seeder, string $slug) {
    $this->seed($seeder);

    $content = (string) Blog::where('slug', $slug)->value('content');

    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', $content, $matches);

    foreach ($matches[1] as $href) {
        expect($href)->toStartWith('/blog/');
    }

    foreach (['indeed', 'glassdoor', 'seek.com', 'salaryexpert', 'careers.marriott.com/electrician'] as $banned) {
        expect(strtolower($content))->not->toContain($banned);
    }
})->with('electrician guides');

it('renders each guide', function (string $seeder, string $slug) {
    $this->seed($seeder);

    get('/blog/'.$slug)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
})->with('electrician guides');

it('corrects the Canadian certification claim', function (string $claim) {
    $this->seed(ElectricianJobsAbroadBlogSeeder::class);

    expect(Blog::where('slug', 'electrician-jobs-abroad-with-visa-sponsorship')->value('content'))->toContain($claim);
})->with([
    'nine provinces' => 'compulsory in Newfoundland and Labrador, Nova Scotia, Prince Edward Island, New Brunswick, Quebec, Ontario, Manitoba, Saskatchewan and Alberta',
    'bc compulsory' => 'compulsory trades from 1 December 2022',
    'domestic and rural' => 'electrician (domestic and rural)',
    'noc codes' => 'NOC 72200 and 72201, TEER 2',
    'october draw' => '<td style="padding:10px;">1 October 2026</td><td style="padding:10px;">3,500</td><td style="padding:10px;">476</td>',
    'april draw' => '<td style="padding:10px;">2 April 2026</td><td style="padding:10px;">3,000</td><td style="padding:10px;">477</td>',
    'fstp hours' => '3,120 hours',
    'no category points' => 'The category does not add CRS points',
]);

it('states the Australian route in the right order', function (string $claim) {
    $this->seed(ElectricianJobsAbroadBlogSeeder::class);

    expect(Blog::where('slug', 'electrician-jobs-abroad-with-visa-sponsorship')->value('content'))->toContain($claim);
})->with([
    'anzsco' => 'ANZSCO 341111',
    'mltssl' => 'Medium and Long-term Strategic Skills List',
    'csol' => 'Core Skills Occupation List',
    'osap' => 'Offshore Skills Assessment Program',
    'otsr' => 'Offshore Technical Skills Record',
    'provisional licence' => 'Provisional licence.',
    'tss program' => 'TSS Skills Assessment Program',
]);

it('carries only official pay figures on the salary guide', function (string $claim) {
    $this->seed(ElectricianSalaryUaeUkUsaBlogSeeder::class);

    expect(Blog::where('slug', 'electrician-salary-in-the-uae-uk-and-usa')->value('content'))->toContain($claim);
})->with([
    'bls median' => '$63,190',
    'bls mean' => '$71,490',
    'bls top decile' => '$108,510',
    'hawaii' => '$92,870',
    'california' => '$85,860',
    'mississippi' => '$58,000',
    'projection' => '72,700 openings a year',
    'jib electrician' => '£18.38',
    'jib approved' => '£20.08',
    'jib technician' => '£22.70',
    'jib london' => '£20.58',
    'jib date' => '5 January 2026',
    'tsl going rate' => '£38,800',
    'no uae minimum' => 'no statutory minimum wage',
    'recruitment costs' => 'Article 6 of Federal Decree-Law No. 33 of 2021',
    'wps' => 'Ministerial Resolution No. 340 of 2026',
    'shift exception' => 'does not apply to workers on shifts',
]);

it('drops the job-board figures from the salary guide', function (string $figure) {
    $this->seed(ElectricianSalaryUaeUkUsaBlogSeeder::class);

    expect(Blog::where('slug', 'electrician-salary-in-the-uae-uk-and-usa')->value('content'))->not->toContain($figure);
})->with([
    'indeed uae' => '1,672',
    'salaryexpert uae' => '14,700',
    'indeed uk' => '41,004',
    'fake ons' => '39,647',
    'wrong hawaii' => '87,690',
]);

it('compares industries on BLS medians instead of a flat percentage', function (string $claim) {
    $this->seed(IndustrialVsHouseWiringElectricianBlogSeeder::class);

    expect(Blog::where('slug', 'industrial-vs-house-wiring-electrician-which-pays-more')->value('content'))->toContain($claim);
})->with([
    'residential' => '$63,580',
    'contractors' => '$61,570',
    'manufacturing' => '$74,550',
    'refining' => '$107,840',
    'utilities' => '$106,640',
    'oil and gas' => '$103,640',
    'repairers' => '$74,090',
    'substation' => '$103,020',
    'not a fixed gap' => 'It is not a fixed 30 to 40 per cent',
    'inside wireman hours' => 'at least 8,000 hours on the job',
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
    'canada' => [ElectricianJobsAbroadBlogSeeder::class, 'Construction and Industrial Electrician — Canadian', 'https://www.jobbank.gc.ca/jobsearch', 'Canada'],
    'australia' => [ElectricianJobsAbroadBlogSeeder::class, 'Licensed Electrician — Australian', 'https://www.workforceaustralia.gov.au/individuals/jobs/search', 'Australia'],
    'uk' => [ElectricianSalaryUaeUkUsaBlogSeeder::class, 'Electrician and Approved Electrician — UK', 'https://www.gov.uk/find-a-job', 'United Kingdom'],
    'uae' => [ElectricianSalaryUaeUkUsaBlogSeeder::class, 'Electrician — UAE', 'https://u.ae/en/information-and-services/jobs', 'United Arab Emirates'],
    'us' => [IndustrialVsHouseWiringElectricianBlogSeeder::class, 'Industrial and Maintenance Electrician — US', 'https://www.usa.gov/job-search', 'United States'],
]);

it('cross-links the cluster in both directions', function (string $fromSeeder, string $fromSlug, string $toSeeder, string $toSlug) {
    $this->seed($fromSeeder);
    $this->seed($toSeeder);

    expect(Blog::where('slug', $fromSlug)->value('content'))->toContain('/blog/'.$toSlug)
        ->and(Blog::where('slug', $toSlug)->value('content'))->toContain('/blog/'.$fromSlug);
})->with([
    'abroad and salary' => [ElectricianJobsAbroadBlogSeeder::class, 'electrician-jobs-abroad-with-visa-sponsorship', ElectricianSalaryUaeUkUsaBlogSeeder::class, 'electrician-salary-in-the-uae-uk-and-usa'],
    'abroad and industrial' => [ElectricianJobsAbroadBlogSeeder::class, 'electrician-jobs-abroad-with-visa-sponsorship', IndustrialVsHouseWiringElectricianBlogSeeder::class, 'industrial-vs-house-wiring-electrician-which-pays-more'],
    'salary and industrial' => [ElectricianSalaryUaeUkUsaBlogSeeder::class, 'electrician-salary-in-the-uae-uk-and-usa', IndustrialVsHouseWiringElectricianBlogSeeder::class, 'industrial-vs-house-wiring-electrician-which-pays-more'],
    'uk and abroad' => [ElectricianJobsUkBlogSeeder::class, 'electrician-jobs-in-uk', ElectricianJobsAbroadBlogSeeder::class, 'electrician-jobs-abroad-with-visa-sponsorship'],
    'uk and salary' => [ElectricianJobsUkBlogSeeder::class, 'electrician-jobs-in-uk', ElectricianSalaryUaeUkUsaBlogSeeder::class, 'electrician-salary-in-the-uae-uk-and-usa'],
    'uk and industrial' => [ElectricianJobsUkBlogSeeder::class, 'electrician-jobs-in-uk', IndustrialVsHouseWiringElectricianBlogSeeder::class, 'industrial-vs-house-wiring-electrician-which-pays-more'],
    'plumber licence and abroad' => [LicensedPlumberCanadaAustraliaBlogSeeder::class, 'how-to-become-a-licensed-plumber-in-canada-or-australia', ElectricianJobsAbroadBlogSeeder::class, 'electrician-jobs-abroad-with-visa-sponsorship'],
    'plumber pay and salary' => [PlumberSalaryUkUsaBlogSeeder::class, 'plumber-salary-in-the-uk-and-usa', ElectricianSalaryUaeUkUsaBlogSeeder::class, 'electrician-salary-in-the-uae-uk-and-usa'],
]);
