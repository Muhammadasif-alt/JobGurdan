<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\CarpenterJobsUkCanadaAustraliaBlogSeeder;
use Database\Seeders\PainterDecoratorJobsGulfBlogSeeder;
use Database\Seeders\WelderSalaryUsaBlogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

dataset('trades guides', [
    'welder salary usa' => [WelderSalaryUsaBlogSeeder::class, 'welder-salary-in-usa', 'blogs/welder-salary-in-usa.jpg', ['welder-salary-in-usa-skyline.jpg', 'welder-salary-in-usa-harbour.jpg']],
    'carpenter uk canada australia' => [CarpenterJobsUkCanadaAustraliaBlogSeeder::class, 'carpenter-jobs-in-the-uk-canada-and-australia', 'blogs/carpenter-jobs-uk-canada-australia.jpg', ['carpenter-jobs-uk-canada-australia-saw.jpg', 'carpenter-jobs-uk-canada-australia-framing.jpg']],
    'painter gulf' => [PainterDecoratorJobsGulfBlogSeeder::class, 'painter-and-decorator-jobs-in-the-gulf', 'blogs/painter-decorator-jobs-gulf.jpg', ['painter-decorator-jobs-gulf-facade.jpg']],
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
})->with('trades guides');

it('carries exactly eight FAQs and eight People Also Search For entries', function (string $seeder, string $slug) {
    $this->seed($seeder);

    $content = Blog::where('slug', $slug)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $pasf = substr($content, (int) strpos($content, 'People Also Search For'));
    $pasf = substr($pasf, 0, (int) strpos($pasf, '<h2>More Job Guides'));

    expect(substr_count($pasf, '<h3>'))->toBe(8);
})->with('trades guides');

it('links to no aggregator and no job ID', function (string $seeder, string $slug) {
    $this->seed($seeder);

    $content = (string) Blog::where('slug', $slug)->value('content');

    preg_match_all('/<a\s[^>]*href="([^"]+)"/i', $content, $matches);

    foreach ($matches[1] as $href) {
        expect($href)->toStartWith('/blog/');
    }

    foreach (['indeed', 'glassdoor', 'ziprecruiter', 'gulftalent', 'naukrigulf', 'bayt', 'salaryexpert', 'seek.com', 'jid-'] as $banned) {
        expect(strtolower($content))->not->toContain($banned);
    }
})->with('trades guides');

it('renders each guide', function (string $seeder, string $slug) {
    $this->seed($seeder);

    get('/blog/'.$slug)
        ->assertOk()
        ->assertSee('People Also Search For')
        ->assertSee('"FAQPage"', false);
})->with('trades guides');

it('uses the confirmed BLS and Job Bank welder figures', function (string $claim) {
    $this->seed(WelderSalaryUsaBlogSeeder::class);

    expect(Blog::where('slug', 'welder-salary-in-usa')->value('content'))->toContain($claim);
})->with([
    'median' => '$53,750',
    'hourly' => '$25.84',
    'bottom decile' => '$39,240',
    'top decile' => '$77,530',
    'specialty trade contractors' => '$59,440',
    'repair and maintenance' => '$56,080',
    'heavy and civil' => '$76,690',
    'projection period' => '2025 to 2035',
    'openings' => 'About 40,300 openings a year',
    'no process split' => 'BLS does not split welder pay by welding process',
    'job bank median' => 'C$30.00',
    'alberta' => 'C$38.00',
]);

it('drops the aggregator and listing figures from the welder guide', function (string $figure) {
    $this->seed(WelderSalaryUsaBlogSeeder::class);

    expect(Blog::where('slug', 'welder-salary-in-usa')->value('content'))->not->toContain($figure);
})->with([
    'indeed tig' => '25.48',
    'indeed mig' => '22.14',
    'ford listing' => '44.77',
    'listing range' => '125',
    'unconfirmed manufacturing median' => '51,210',
]);

it('states the current UK rule and official Canadian wages for carpenters', function (string $claim) {
    $this->seed(CarpenterJobsUkCanadaAustraliaBlogSeeder::class);

    expect(Blog::where('slug', 'carpenter-jobs-in-the-uk-canada-and-australia')->value('content'))->toContain($claim);
})->with([
    'rule change date' => '22 July 2025',
    'tsl' => 'Temporary Shortage List',
    'not on it' => 'carpenters and joiners were not on it',
    'threshold' => 'GBP 41,700',
    'job bank median' => 'C$32.12',
    'quebec' => 'C$36.84',
    'anzsco' => '331212',
    'skills in demand' => 'Skills in Demand visa (subclass 482)',
    'tra' => 'Trades Recognition Australia',
]);

it('drops the stale ISL rates and board figures from the carpenter guide', function (string $figure) {
    $this->seed(CarpenterJobsUkCanadaAustraliaBlogSeeder::class);

    expect(Blog::where('slug', 'carpenter-jobs-in-the-uk-canada-and-australia')->value('content'))->not->toContain($figure);
})->with([
    'isl rate' => '33,400',
    'isl lower rate' => '27,800',
    'job count' => '1,222',
    'seek rate' => 'A$60',
]);

it('states the Gulf rules instead of job-board pay for painters', function (string $claim) {
    $this->seed(PainterDecoratorJobsGulfBlogSeeder::class);

    expect(Blog::where('slug', 'painter-and-decorator-jobs-in-the-gulf')->value('content'))->toContain($claim);
})->with([
    'no minimum wage' => 'Neither country sets a wage floor for a foreign painter',
    'recruitment costs' => 'Article 6 of Federal Decree-Law No. 33 of 2021',
    'professional verification' => 'Professional Verification',
    'uae midday break' => 'from 12:30pm to 3pm',
    'midday fine' => 'AED 5,000 per worker',
    'wps' => 'Ministerial Resolution No. 340 of 2026',
    'saudi ban' => 'noon to 3pm',
]);

it('drops the aggregator pay and vacancy IDs from the painter guide', function (string $figure) {
    $this->seed(PainterDecoratorJobsGulfBlogSeeder::class);

    expect(Blog::where('slug', 'painter-and-decorator-jobs-in-the-gulf')->value('content'))->not->toContain($figure);
})->with([
    'naukrigulf average' => '2,978',
    'gmg job id' => '8093244',
    'accor job id' => '111654',
    'indeed range' => '2,000',
    'indeed low range' => '1,400',
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
        ->and($job->category->slug)->toBe('construction-trades')
        ->and($job->description)->toContain('not by JobGader')
        ->and(app(StructuredDataService::class)->describesSingleVacancy($job->application_url, $job))->toBeFalse();
})->with([
    'us welder' => [WelderSalaryUsaBlogSeeder::class, 'Welder — US', 'https://www.usa.gov/job-search', 'United States'],
    'uk carpenter' => [CarpenterJobsUkCanadaAustraliaBlogSeeder::class, 'Carpenter and Joiner — UK', 'https://www.gov.uk/find-a-job', 'United Kingdom'],
    'canada carpenter' => [CarpenterJobsUkCanadaAustraliaBlogSeeder::class, 'Carpenter — Canadian', 'https://www.jobbank.gc.ca/jobsearch', 'Canada'],
    'australia carpenter' => [CarpenterJobsUkCanadaAustraliaBlogSeeder::class, 'Carpenter — Australian', 'https://www.workforceaustralia.gov.au/individuals/jobs/search', 'Australia'],
    'uae painter' => [PainterDecoratorJobsGulfBlogSeeder::class, 'Painter and Decorator — UAE', 'https://u.ae/en/information-and-services/jobs', 'United Arab Emirates'],
    'saudi painter' => [PainterDecoratorJobsGulfBlogSeeder::class, 'Painter — Saudi', 'https://www.hrsd.gov.sa/en', 'Saudi Arabia'],
]);

it('cross-links the new guides in both directions', function (string $fromSeeder, string $fromSlug, string $toSeeder, string $toSlug) {
    $this->seed($fromSeeder);
    $this->seed($toSeeder);

    expect(Blog::where('slug', $fromSlug)->value('content'))->toContain('/blog/'.$toSlug)
        ->and(Blog::where('slug', $toSlug)->value('content'))->toContain('/blog/'.$fromSlug);
})->with([
    'welder and carpenter' => [WelderSalaryUsaBlogSeeder::class, 'welder-salary-in-usa', CarpenterJobsUkCanadaAustraliaBlogSeeder::class, 'carpenter-jobs-in-the-uk-canada-and-australia'],
    'carpenter and painter' => [CarpenterJobsUkCanadaAustraliaBlogSeeder::class, 'carpenter-jobs-in-the-uk-canada-and-australia', PainterDecoratorJobsGulfBlogSeeder::class, 'painter-and-decorator-jobs-in-the-gulf'],
]);

it('links out to the existing companion guides', function (string $seeder, string $slug, string $target) {
    $this->seed($seeder);

    expect(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.$target);
})->with([
    'welder to canada' => [WelderSalaryUsaBlogSeeder::class, 'welder-salary-in-usa', 'welder-jobs-in-canada'],
    'carpenter to usa' => [CarpenterJobsUkCanadaAustraliaBlogSeeder::class, 'carpenter-jobs-in-the-uk-canada-and-australia', 'carpenter-jobs-in-usa'],
    'painter to gulf plumber' => [PainterDecoratorJobsGulfBlogSeeder::class, 'painter-and-decorator-jobs-in-the-gulf', 'plumber-jobs-in-uae-and-saudi-arabia'],
    'painter to gulf ac' => [PainterDecoratorJobsGulfBlogSeeder::class, 'painter-and-decorator-jobs-in-the-gulf', 'ac-technician-jobs-in-dubai-and-saudi-arabia'],
]);
