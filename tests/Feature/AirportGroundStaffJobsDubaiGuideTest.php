<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\AirportGroundStaffJobsDubaiBlogSeeder;
use Database\Seeders\DpWorldPortJobsUaeBlogSeeder;
use Database\Seeders\EmiratesCabinCrewJobsUaeBlogSeeder;
use Database\Seeders\EtihadAirportJobsUaeBlogSeeder;
use Database\Seeders\HeathrowAirportJobsUkBlogSeeder;
use Illuminate\Support\Str;

const DXB_GROUND_SLUG = 'airport-ground-staff-jobs-in-dubai';

beforeEach(function () {
    $this->seed(AirportGroundStaffJobsDubaiBlogSeeder::class);
});

it('publishes the guide inside the snippet limits', function () {
    $blog = Blog::where('slug', DXB_GROUND_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/airport-ground-staff-jobs-dubai.jpg')
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
    $blog = Blog::where('slug', DXB_GROUND_SLUG)->first();

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
    $content = Blog::where('slug', DXB_GROUND_SLUG)->value('content');

    expect(app(StructuredDataService::class)->faqsFromHtml($content))->toHaveCount(8);

    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'More Job Guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);

    $this->get('/blog/'.DXB_GROUND_SLUG)->assertOk()->assertSee('"FAQPage"', false);
});

it('links only to employer careers sites, never to an aggregator', function () {
    $content = Blog::where('slug', DXB_GROUND_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Airport Ground Staff%')->first();

    // The brief opened and closed on ae.indeed.com.
    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'bayt.com', 'gulftalent', 'naukrigulf'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator);
    }

    expect($job->application_url)->toBe('https://www.emiratesgroupcareers.com/search-and-apply/')
        ->and($content)->toContain('careers.dubaiairports.ae/en/search-and-apply/')
        ->and($content)->toContain('dnata.com/en/careers/')
        ->and($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i');
});

it('states the shift work exception to the overtime premium', function () {
    $content = Blog::where('slug', DXB_GROUND_SLUG)->value('content');

    // The reason this page exists: the night premium most guides promise is
    // expressly disapplied to shift workers, and ground staff are shift workers.
    expect($content)->toContain('This rule does not apply on workers who work on basis of shifts.')
        ->toContain('plus 25 per cent of that pay')
        ->toContain('it could increase to 50 per cent if overtime is done between 10 pm and 4 am')
        ->toContain('shall not exceed two hours in one day')
        ->toContain('ask for the shift allowance in writing');
});

it('gives the hours, breaks, rest day and summer rules verbatim', function () {
    $content = Blog::where('slug', DXB_GROUND_SLUG)->value('content');

    expect($content)->toContain('8 hours per day, or 48 hours per week')
        ->toContain('Breaks are not calculated within the working hours.')
        ->toContain('to a substitute rest day')
        ->toContain('12.30 pm and 3 pm from 15 June to 15 September')
        ->toContain('fully paid annual leave of 30 days');
});

it('uses the 2026 wage protection resolution rather than the superseded one', function () {
    $content = Blog::where('slug', DXB_GROUND_SLUG)->value('content');

    expect($content)->toContain('Ministerial Resolution No. 340 of 2026')
        ->toContain('due on the first day of each Gregorian month')
        ->toContain('at least 85 per cent of the total wages')
        ->toContain('automatic registration of an individual or collective labour dispute')
        // The stale rule this page exists to displace is named and disclaimed,
        // never presented as current.
        ->toContain('If a guide tells you wages must arrive within 15 days of the due date, it is describing the superseded 2022 resolution.');
});

it('names the medical tests and the two results that end the application', function () {
    $content = Blog::where('slug', DXB_GROUND_SLUG)->value('content');

    expect($content)->toContain('Cabinet Resolution No. 5 of 2016')
        ->toContain('HIV/AIDS, hepatitis B, leprosy, syphilis and tuberculosis')
        ->toContain('residency is denied or not renewed for positive cases')
        ->toContain('will be treated in the UAE until recovery');
});

it('gives the visa sequence and the visit visa prohibition', function () {
    $content = Blog::where('slug', DXB_GROUND_SLUG)->value('content');

    expect($content)->toContain('valid for at least six months')
        ->toContain('remains valid for two months from the date of issue')
        ->toContain('which is valid for two years')
        ->toContain("within 14 days of the employee's arrival in the UAE")
        ->toContain('The UAE law strictly prohibits working while holding a visit or tourist visa');
});

it('tells the reader the two documents must match and that Urdu is an option', function () {
    $content = Blog::where('slug', DXB_GROUND_SLUG)->value('content');

    expect($content)->toContain('must be consistent with the job offer you have signed in your country')
        ->toContain('maintain a copy of the job offer you have signed')
        ->toContain('Bengali, Chinese, Dari, Hindi, Malayalam, Nepalese, Sinhalese, Tamil and Urdu')
        ->toContain('ask for the Urdu version');
});

it('carries the fee prohibition on both sides of the journey', function () {
    $content = Blog::where('slug', DXB_GROUND_SLUG)->value('content');

    expect($content)->toContain('Charging recruitment fees to prospective employees is illegal in the UAE.')
        ->toContain('shall be borne by the employer')
        ->toContain("the confiscation of workers' passports is prohibited")
        // Pakistan: the criminal provision, quoted, rather than a fee figure.
        ->toContain('Emigration Ordinance 1979')
        ->toContain('imprisonment for term, which may extend to fourteen years')
        ->toContain('Protector of Emigrants');
});

it('refuses to publish a salary or an unverified promoter fee cap', function () {
    $blog = Blog::where('slug', DXB_GROUND_SLUG)->first();
    $job = Job::where('position', 'like', 'Airport Ground Staff%')->first();

    expect($blog->content)->toContain('there is no minimum salary stipulated in the UAE Labour Law')
        ->toContain('we will not repeat a guess')
        // The BEOE service charge cap could not be reached from an official host.
        ->toContain('the number is not asserted on a secondary source')
        ->and($blog->content)->not->toMatch('/AED\s?[0-9],[0-9]{3}\s?(a month|per month|monthly|salary)/i')
        ->and($job->salary_minimum)->toBeNull()
        ->and($job->salary_maximum)->toBeNull();
});

it('links into the UAE and aviation cluster in both directions', function () {
    $this->seed(EtihadAirportJobsUaeBlogSeeder::class);
    $this->seed(EmiratesCabinCrewJobsUaeBlogSeeder::class);
    $this->seed(DpWorldPortJobsUaeBlogSeeder::class);
    $this->seed(HeathrowAirportJobsUkBlogSeeder::class);

    $content = Blog::where('slug', DXB_GROUND_SLUG)->value('content');

    foreach ([
        'how-to-apply-for-etihad-airport-jobs-in-uae',
        'how-to-apply-for-emirates-cabin-crew-jobs-in-uae',
        'how-to-apply-for-dp-world-port-jobs-in-uae',
        'how-to-apply-for-heathrow-airport-jobs-in-the-uk',
    ] as $slug) {
        expect($content)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.DXB_GROUND_SLUG);
    }
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'Airport Ground Staff%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not a single vacancy');
});

it('repairs the post instead of duplicating it', function () {
    Blog::where('slug', DXB_GROUND_SLUG)->update(['content' => 'stale copy']);

    $this->seed(AirportGroundStaffJobsDubaiBlogSeeder::class);

    expect(Job::where('position', 'like', 'Airport Ground Staff%')->count())->toBe(1)
        ->and(Blog::where('slug', DXB_GROUND_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', DXB_GROUND_SLUG)->value('content'))->not->toBe('stale copy');
});
