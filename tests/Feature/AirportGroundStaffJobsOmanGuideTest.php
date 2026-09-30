<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\AirportGroundStaffJobsDubaiBlogSeeder;
use Database\Seeders\AirportGroundStaffJobsOmanBlogSeeder;
use Database\Seeders\DpWorldPortJobsUaeBlogSeeder;
use Database\Seeders\EmiratesCabinCrewJobsUaeBlogSeeder;
use Database\Seeders\EtihadAirportJobsUaeBlogSeeder;
use Database\Seeders\HeathrowAirportJobsUkBlogSeeder;
use Database\Seeders\OmanAirCabinCrewJobsBlogSeeder;
use Database\Seeders\PdoEngineeringJobsOmanBlogSeeder;
use Illuminate\Support\Str;

const OMAN_GROUND_SLUG = 'airport-ground-staff-jobs-in-oman';

beforeEach(function () {
    $this->seed(AirportGroundStaffJobsOmanBlogSeeder::class);
});

it('publishes the guide inside the snippet limits', function () {
    $blog = Blog::where('slug', OMAN_GROUND_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/airport-ground-staff-jobs-oman.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->meta_title)->not->toMatch('/[&<>"]/')
        ->and(count(explode(',', $blog->tags)))->toBe(8);

    foreach ([$blog->meta_title, $blog->meta_description, $blog->tags] as $field) {
        expect(mb_check_encoding($field, 'ASCII'))->toBeTrue($field);
    }
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', OMAN_GROUND_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[a-z0-9-]+\.jpg)#', $blog->content, $inline);

    expect($inline[1])->toHaveCount(2);

    foreach (array_merge([$blog->featured_image], $inline[1]) as $path) {
        $file = storage_path('app/public/'.$path);
        expect(file_exists($file))->toBeTrue($path.' is missing')
            ->and(filesize($file))->toBeLessThan(400 * 1024, $path.' is too heavy');
    }

    expect($blog->content)->not->toContain('.png');
});

it('carries eight FAQs and eight People Also Search For entries', function () {
    $content = Blog::where('slug', OMAN_GROUND_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'More Job Guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);

    $this->get('/blog/'.OMAN_GROUND_SLUG)->assertOk()->assertSee('"FAQPage"', false);
});

it('links only to employer and government sites, never to an aggregator', function () {
    $content = Blog::where('slug', OMAN_GROUND_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Airport Ground Staff, Oman%')->first();

    // The brief opened, closed and twice interrupted itself with om.indeed.com.
    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'bayt.com', 'gulftalent', 'naukrigulf'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator);
    }

    expect($job->application_url)->toBe('https://services.omanair.com/om/en/careers')
        ->and($content)->toContain('eservices.omanairports.co.om/Eservices/Recruitment/Vacancies.aspx')
        ->and($content)->toContain('mol.gov.om')
        ->and($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i');
});

it('publishes the working eRecruit address and not the one that fails', function () {
    $content = Blog::where('slug', OMAN_GROUND_SLUG)->value('content');

    // The brief's eRecruit link is on services.omanair.com and returns 502.
    expect($content)->toContain('https://www.omanair.com/erecruit/guest/latestActVacancy_getAllActiveVacancy.do')
        ->not->toContain('https://services.omanair.com/erecruit');
});

it('builds the page on Omanisation rather than repeating the Dubai guide', function () {
    $content = Blog::where('slug', OMAN_GROUND_SLUG)->value('content');

    // The reason this page exists: in Oman the first question is whether an
    // expatriate may hold the occupation at all.
    expect($content)->toContain('Resolution 235/2022')
        ->toContain('Resolution 501/2024')
        ->toContain('OMR 500 per month for each unfilled position')
        ->toContain('electronic compliance certificate')
        // The restriction bites on new permits, not on people already holding one.
        ->toContain('stops <em>new</em> expatriate work permits')
        // The leap the page refuses to make.
        ->toContain('We are not going to tell you that airport ground staff is, or is not, on the restricted list');
});

it('gives the Oman hours and leave figures, not the UAE ones', function () {
    $content = Blog::where('slug', OMAN_GROUND_SLUG)->value('content');

    expect($content)->toContain('Royal Decree 53/2023')
        ->toContain('maximum of 40 hours a week')
        ->toContain('may not exceed 12 hours in a single day')
        // Consent is the real difference from the UAE and most guides miss it.
        ->toContain('overtime requires the employee')
        ->toContain('capped at 15 days in a year')
        ->toContain('21 days of paid annual leave')
        // The honest comparison: Oman is not uniformly better.
        ->toContain("Oman's statutory 21 days is <em>lower</em> than the UAE's 30");
});

it('records what the official portal actually showed', function () {
    $content = Blog::where('slug', OMAN_GROUND_SLUG)->value('content');

    expect($content)->toContain('Service Desk Operator')
        ->toContain('Senior Accountant')
        ->toContain('Not one of them was a ground staff role')
        // Oman Airports is not only Muscat, and this is the least contested tip.
        ->toContain('Marmul')
        ->toContain('Qarn Alam')
        ->toContain('Fahud');
});

it('refuses to publish a salary and explains why the minimum wage does not help', function () {
    $blog = Blog::where('slug', OMAN_GROUND_SLUG)->first();
    $job = Job::where('position', 'like', 'Airport Ground Staff, Oman%')->first();

    expect($blog->content)->toContain('There is no statutory minimum wage for expatriates')
        ->toContain('OMR 225 basic plus OMR 100 allowance')
        ->toContain('we are not publishing a figure')
        // The practical advice that replaces the number.
        ->toContain('the basic wage is the figure that drives your overtime rate')
        ->and($blog->content)->not->toMatch('/OMR\s?[0-9,]+\s?(a month|per month|monthly)\s?(for|as)\s?(ground|airport)/i')
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull();
});

it('states that the NOC requirement was removed', function () {
    $content = Blog::where('slug', OMAN_GROUND_SLUG)->value('content');

    expect($content)->toContain('Decision 157/2020')
        ->toContain('1 January 2021')
        ->toContain('two-year ban')
        // Transfer is easier, not unconditional.
        ->toContain('must have obtained labour clearance')
        ->toContain('they are describing the position before 2021');
});

it('does not assert anything about the handler it could not reach', function () {
    $content = Blog::where('slug', OMAN_GROUND_SLUG)->value('content');

    // The brief claimed TRANSOM's careers page showed no openings. No TRANSOM
    // site resolved during checking, so the page says so instead of repeating it.
    expect($content)->toContain('had no reachable website from any of the addresses we tried')
        ->and(strtolower($content))->not->toContain('transom');
});

it('carries the fee prohibition and the Pakistani criminal provision', function () {
    $content = Blog::where('slug', OMAN_GROUND_SLUG)->value('content');

    expect($content)->toContain('Emigration Ordinance 1979')
        ->toContain('imprisonment for term, which may extend to fourteen years')
        ->toContain('Protector of Emigrants')
        // A test the reader can run for free.
        ->toContain('HR/26/932');
});

it('links into the aviation cluster in both directions', function () {
    $this->seed(AirportGroundStaffJobsDubaiBlogSeeder::class);
    $this->seed(EtihadAirportJobsUaeBlogSeeder::class);
    $this->seed(EmiratesCabinCrewJobsUaeBlogSeeder::class);
    $this->seed(DpWorldPortJobsUaeBlogSeeder::class);
    $this->seed(HeathrowAirportJobsUkBlogSeeder::class);
    $this->seed(OmanAirCabinCrewJobsBlogSeeder::class);
    $this->seed(PdoEngineeringJobsOmanBlogSeeder::class);

    $content = Blog::where('slug', OMAN_GROUND_SLUG)->value('content');

    foreach ([
        // The two guides we already had on Oman, which this page must join up with.
        'how-to-apply-for-oman-air-cabin-crew-jobs',
        'how-to-apply-for-pdo-engineering-jobs-in-oman',
        'airport-ground-staff-jobs-in-dubai',
        'how-to-apply-for-etihad-airport-jobs-in-uae',
        'how-to-apply-for-emirates-cabin-crew-jobs-in-uae',
        'how-to-apply-for-dp-world-port-jobs-in-uae',
        'how-to-apply-for-heathrow-airport-jobs-in-the-uk',
    ] as $slug) {
        expect($content)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.OMAN_GROUND_SLUG);
    }
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'Airport Ground Staff, Oman%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not a single vacancy');
});

it('repairs the post instead of duplicating it', function () {
    Blog::where('slug', OMAN_GROUND_SLUG)->update(['content' => 'stale copy']);

    $this->seed(AirportGroundStaffJobsOmanBlogSeeder::class);

    expect(Job::where('position', 'like', 'Airport Ground Staff, Oman%')->count())->toBe(1)
        ->and(Blog::where('slug', OMAN_GROUND_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', OMAN_GROUND_SLUG)->value('content'))->not->toBe('stale copy');
});
