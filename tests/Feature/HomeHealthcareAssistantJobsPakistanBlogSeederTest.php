<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\DataEntryJobsPakistanBlogSeeder;
use Database\Seeders\HealthcareAdministratorJobsPakistanBlogSeeder;
use Database\Seeders\HomeHealthcareAssistantJobsPakistanBlogSeeder;
use Database\Seeders\MedicalAssistantJobsUsaBlogSeeder;
use Database\Seeders\MedicalBillingAssistantJobsPakistanBlogSeeder;
use Illuminate\Support\Str;

const HOMECARE_SLUG = 'home-healthcare-assistant-jobs-in-pakistan';

beforeEach(function () {
    $this->seed(HomeHealthcareAssistantJobsPakistanBlogSeeder::class);
});

it('publishes the home healthcare guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', HOMECARE_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/home-healthcare-assistant-jobs-pakistan.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('keeps the fields Google reads to ASCII', function () {
    $blog = Blog::where('slug', HOMECARE_SLUG)->first();

    foreach ([$blog->meta_title, $blog->meta_description, $blog->tags] as $field) {
        expect(mb_check_encoding($field, 'ASCII'))->toBeTrue($field);
    }
});

it('ships every image it references, and none of them is a heavyweight PNG', function () {
    $blog = Blog::where('slug', HOMECARE_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[a-z0-9-]+\.jpg)#', $blog->content, $inline);

    expect($inline[1])->toHaveCount(2);

    foreach (array_merge([$blog->featured_image], $inline[1]) as $path) {
        $file = storage_path('app/public/'.$path);
        expect(file_exists($file))->toBeTrue($path.' is missing')
            ->and(filesize($file))->toBeLessThan(400 * 1024, $path.' is too heavy for a blog image');
    }

    expect($blog->content)->not->toContain('.png');
});

it('renders with its long-tail sections', function () {
    $this->get('/blog/'.HOMECARE_SLUG)
        ->assertOk()
        ->assertSee('Home Healthcare Assistant Jobs in Pakistan')
        ->assertSee('People Also Search For')
        ->assertSee('online.pnmc.gov.pk');
});

it('carries eight FAQs in markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', HOMECARE_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8);

    $this->get('/blog/'.HOMECARE_SLUG)->assertSee('"FAQPage"', false);
});

it('offers eight People Also Search For entries', function () {
    $content = Blog::where('slug', HOMECARE_SLUG)->value('content');
    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);
});

it('sends nobody to a job aggregator, in the guide or on the listing', function () {
    $content = Blog::where('slug', HOMECARE_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Home Healthcare Assistant%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'rozee.pk', 'mustakbil'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url.' '.$job->description))->not->toContain($aggregator);
    }

    expect($job->application_url)->toBe('https://saharahomehealthcare.com/jobs.php');
});

it('never links a vacancy by its job identifier', function () {
    $content = Blog::where('slug', HOMECARE_SLUG)->value('content');

    // The brief linked the AKU vacancy as job-detail.aspx?JobID=7723.
    expect($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i')
        ->and($content)->not->toContain('job-detail.aspx')
        ->and($content)->toContain('aku.edu/vacancies/pages/home.aspx');
});

it('describes the AKU opening as the traineeship it actually is', function () {
    $content = Blog::where('slug', HOMECARE_SLUG)->value('content');

    // AKU publishes a four-week programme whose stipend follows completion.
    // The brief presented it as an entry-level job for beginners.
    expect($content)->toContain('four-week traineeship')
        ->toContain('upon successful completion')
        ->toContain('not a salaried job');
});

it('corrects the regulator and the credential', function () {
    $content = Blog::where('slug', HOMECARE_SLUG)->value('content');

    expect($content)->toContain('Pakistan Nursing and Midwifery Council')
        ->toContain('Pakistan has no CNA licence')
        ->toContain('pnc.org.pk no longer resolves')
        // The check a reader can actually run.
        ->toContain('by CNIC number');
});

it('drops the aggregator salary the brief quoted', function () {
    $content = Blog::where('slug', HOMECARE_SLUG)->value('content');

    foreach (['Indeed', 'Holistic Healthcare'] as $rotten) {
        expect($content)->not->toContain($rotten);
    }
});

it('publishes the employer terms it verified', function () {
    $content = Blog::where('slug', HOMECARE_SLUG)->value('content');

    expect($content)->toContain('saharahomehealthcare.com/jobs.php')
        ->toContain('carenest.pk/careers.html')
        ->toContain('24/7 live-in');
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'Home Healthcare Assistant%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('creates one listing and repairs the post instead of duplicating it', function () {
    Blog::where('slug', HOMECARE_SLUG)->update(['content' => 'stale copy']);

    $this->seed(HomeHealthcareAssistantJobsPakistanBlogSeeder::class);

    expect(Job::where('position', 'like', 'Home Healthcare Assistant%')->count())->toBe(1)
        ->and(Blog::where('slug', HOMECARE_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', HOMECARE_SLUG)->value('content'))->not->toBe('stale copy');
});

it('links to and from the guides it sits between', function () {
    $this->seed(MedicalBillingAssistantJobsPakistanBlogSeeder::class);
    $this->seed(HealthcareAdministratorJobsPakistanBlogSeeder::class);
    $this->seed(MedicalAssistantJobsUsaBlogSeeder::class);
    $this->seed(DataEntryJobsPakistanBlogSeeder::class);

    $homecare = Blog::where('slug', HOMECARE_SLUG)->value('content');

    foreach ([
        'medical-billing-assistant-jobs-in-pakistan',
        'healthcare-administrator-jobs-in-pakistan',
        'medical-assistant-jobs-in-usa',
        'data-entry-jobs-in-pakistan',
    ] as $slug) {
        expect($homecare)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.HOMECARE_SLUG);
    }
});
