<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\CarMechanicJobsAustraliaUkCanadaBlogSeeder;
use Database\Seeders\EvTechnicianJobsTrainingBlogSeeder;
use Database\Seeders\MechanicSalaryByCountryBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

dataset('mechanic guides', [
    'jobs' => [CarMechanicJobsAustraliaUkCanadaBlogSeeder::class, 'car-mechanic-jobs-in-australia-uk-and-canada', 'blogs/car-mechanic-jobs-australia-uk-canada.jpg', ['car-mechanic-jobs-australia-uk-canada-underbody.jpg', 'car-mechanic-jobs-australia-uk-canada-engine.jpg']],
    'salary' => [MechanicSalaryByCountryBlogSeeder::class, 'mechanic-salary-by-country', 'blogs/mechanic-salary-by-country.jpg', ['mechanic-salary-by-country-brakes.jpg', 'mechanic-salary-by-country-engine.jpg']],
    'ev' => [EvTechnicianJobsTrainingBlogSeeder::class, 'ev-technician-jobs-training-and-career-guide', 'blogs/ev-technician-jobs-training.jpg', ['ev-technician-jobs-training-battery.jpg', 'ev-technician-jobs-training-diagnostics.jpg']],
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
})->with('mechanic guides');

it('carries exactly eight FAQs and eight People Also Search For entries', function (string $seeder, string $slug) {
    $this->seed($seeder);

    $content = Blog::where('slug', $slug)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $pasf = substr($content, (int) strpos($content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
})->with('mechanic guides');

it('links to no aggregator and no job ID', function (string $seeder, string $slug) {
    $this->seed($seeder);

    $content = (string) Blog::where('slug', $slug)->value('content');

    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', $content, $matches);

    foreach ($matches[1] as $href) {
        expect($href)->toStartWith('/blog/');
    }

    foreach (['indeed', 'glassdoor', 'ziprecruiter', 'seek.com', 'gulftalent', 'naukrigulf', 'bayt', 'salaryexpert'] as $banned) {
        expect(strtolower($content))->not->toContain($banned);
    }
})->with('mechanic guides');

it('renders each guide', function (string $seeder, string $slug) {
    $this->seed($seeder);

    get('/blog/'.$slug)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
})->with('mechanic guides');

it('states the official figures and qualification routes in the jobs guide', function (string $claim) {
    $this->seed(CarMechanicJobsAustraliaUkCanadaBlogSeeder::class);

    expect(Blog::where('slug', 'car-mechanic-jobs-in-australia-uk-and-canada')->value('content'))->toContain($claim);
})->with([
    'jsa employment' => '112,600',
    'jsa weekly' => 'A$1,622 a week',
    'ncs starter' => '&pound;22,000',
    'ncs experienced' => '&pound;42,000',
    'job bank median' => 'C$29.89',
    'anzsco' => '321211',
    'tra' => 'Trades Recognition Australia (TRA)',
    'soc code' => '5231',
    'skill threshold date' => '22 July 2025',
    'red seal' => 'Red Seal trade',
    'ontario' => '310S Automotive Service Technician',
]);

it('drops vacancy pay and live counts from the jobs guide', function (string $figure) {
    $this->seed(CarMechanicJobsAustraliaUkCanadaBlogSeeder::class);

    expect(Blog::where('slug', 'car-mechanic-jobs-in-australia-uk-and-canada')->value('content'))->not->toContain($figure);
})->with([
    'jlr vacancy' => '48,764',
    'arnold clark pay' => '40,000',
    'job bank count' => '1,200',
    'mtaa fill rate' => '39 per cent',
]);

it('uses verified BLS, Job Bank and UAE rules in the salary guide', function (string $claim) {
    $this->seed(MechanicSalaryByCountryBlogSeeder::class);

    expect(Blog::where('slug', 'mechanic-salary-by-country')->value('content'))->toContain($claim);
})->with([
    'median' => '$50,620',
    'bottom decile' => '$34,660',
    'top decile' => '$81,790',
    'dealers' => '$59,920',
    'diesel' => '$61,770',
    'projection period' => '2025 to 2035',
    'growth' => '5 per cent',
    'openings' => '66,200 openings a year',
    'british columbia' => 'C$35.00',
    'no wage floor' => 'No statutory minimum wage for expatriates',
    'recruitment costs' => 'Article 6 of Federal Decree-Law No. 33 of 2021',
    'wps' => 'Ministerial Resolution No. 340 of 2026',
]);

it('drops the job-board and vacancy pay from the salary guide', function (string $figure) {
    $this->seed(MechanicSalaryByCountryBlogSeeder::class);

    expect(Blog::where('slug', 'mechanic-salary-by-country')->value('content'))->not->toContain($figure);
})->with([
    'indeed uae average' => '3,908',
    'indeed uae low' => '1,905',
    'indeed uae high' => '8,016',
    'listing pay' => '3,500',
    'city toyota' => '79,423',
]);

it('states the verified market and training facts in the EV guide', function (string $claim) {
    $this->seed(EvTechnicianJobsTrainingBlogSeeder::class);

    expect(Blog::where('slug', 'ev-technician-jobs-training-and-career-guide')->value('content'))->toContain($claim);
})->with([
    'iea report' => 'Global EV Outlook 2026',
    'iea growth' => 'grew 20 per cent in 2025',
    'one in four' => 'one in four new cars',
    'iea 2026' => '23 million in 2026',
    'navttc level' => 'NVQF Level 3',
    'navttc length' => 'six months',
    'imi techsafe' => 'IMI TechSafe',
    'bls median' => '$50,620',
]);

it('drops the job-board counts and unconfirmed details from the EV guide', function (string $figure) {
    $this->seed(EvTechnicianJobsTrainingBlogSeeder::class);

    $content = strtolower((string) Blog::where('slug', 'ev-technician-jobs-training-and-career-guide')->value('content'));

    expect($content)->not->toContain(strtolower($figure));
})->with([
    'seek count' => '1,200',
    'job bank count' => '1,100',
    'jlr vacancy' => '48,764',
    'lkq' => 'LKQ',
    'jaunt' => 'Jaunt',
    'tesla careers' => 'Tesla',
    'unconfirmed age limit' => '18 to 40',
    'unconfirmed college' => 'Luban',
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
    'australia' => [CarMechanicJobsAustraliaUkCanadaBlogSeeder::class, 'Motor Mechanic — Australian', 'https://www.workforceaustralia.gov.au/individuals/jobs/search', 'Australia'],
    'canada' => [CarMechanicJobsAustraliaUkCanadaBlogSeeder::class, 'Automotive Service Technician — Canadian', 'https://www.jobbank.gc.ca/jobsearch', 'Canada'],
    'us' => [MechanicSalaryByCountryBlogSeeder::class, 'Automotive Service Technician — US', 'https://www.usa.gov/job-search', 'United States'],
    'uk ev' => [EvTechnicianJobsTrainingBlogSeeder::class, 'EV Technician — UK', 'https://www.gov.uk/find-a-job', 'United Kingdom'],
]);

it('cross-links the cluster in both directions', function (string $fromSeeder, string $fromSlug, string $toSeeder, string $toSlug) {
    $this->seed($fromSeeder);
    $this->seed($toSeeder);

    expect(Blog::where('slug', $fromSlug)->value('content'))->toContain('/blog/'.$toSlug)
        ->and(Blog::where('slug', $toSlug)->value('content'))->toContain('/blog/'.$fromSlug);
})->with([
    'jobs and salary' => [CarMechanicJobsAustraliaUkCanadaBlogSeeder::class, 'car-mechanic-jobs-in-australia-uk-and-canada', MechanicSalaryByCountryBlogSeeder::class, 'mechanic-salary-by-country'],
    'jobs and ev' => [CarMechanicJobsAustraliaUkCanadaBlogSeeder::class, 'car-mechanic-jobs-in-australia-uk-and-canada', EvTechnicianJobsTrainingBlogSeeder::class, 'ev-technician-jobs-training-and-career-guide'],
    'salary and ev' => [MechanicSalaryByCountryBlogSeeder::class, 'mechanic-salary-by-country', EvTechnicianJobsTrainingBlogSeeder::class, 'ev-technician-jobs-training-and-career-guide'],
]);
