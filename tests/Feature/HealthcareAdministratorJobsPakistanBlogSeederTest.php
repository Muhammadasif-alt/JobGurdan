<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\CallCenterJobsPakistanBlogSeeder;
use Database\Seeders\HealthcareAdministratorJobsPakistanBlogSeeder;
use Database\Seeders\HomeHealthcareAssistantJobsPakistanBlogSeeder;
use Database\Seeders\MedicalBillingAssistantJobsPakistanBlogSeeder;
use Database\Seeders\MedicalReceptionistJobsUkBlogSeeder;
use Illuminate\Support\Str;

const ADMIN_SLUG = 'healthcare-administrator-jobs-in-pakistan';

beforeEach(function () {
    $this->seed(HealthcareAdministratorJobsPakistanBlogSeeder::class);
});

it('publishes the healthcare administrator guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', ADMIN_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/healthcare-administrator-jobs-pakistan.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('keeps the fields Google reads to ASCII', function () {
    $blog = Blog::where('slug', ADMIN_SLUG)->first();

    foreach ([$blog->meta_title, $blog->meta_description, $blog->tags] as $field) {
        expect(mb_check_encoding($field, 'ASCII'))->toBeTrue($field);
    }
});

it('ships every image it references, and none of them is a heavyweight PNG', function () {
    $blog = Blog::where('slug', ADMIN_SLUG)->first();

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
    $this->get('/blog/'.ADMIN_SLUG)
        ->assertOk()
        ->assertSee('Healthcare Administrator Jobs in Pakistan')
        ->assertSee('People Also Search For')
        ->assertSee('njp.gov.pk');
});

it('carries eight FAQs in markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', ADMIN_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8);

    $this->get('/blog/'.ADMIN_SLUG)->assertSee('"FAQPage"', false);
});

it('offers eight People Also Search For entries', function () {
    $content = Blog::where('slug', ADMIN_SLUG)->value('content');
    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);
});

it('sends nobody to a job aggregator, in the guide or on the listing', function () {
    $content = Blog::where('slug', ADMIN_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Healthcare Administrator%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'rozee.pk', 'mustakbil'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url.' '.$job->description))->not->toContain($aggregator);
    }

    expect($job->application_url)->toBe('https://ulh.org.pk/careers/');
});

it('drops the salary claim whose employer does not exist', function () {
    $content = Blog::where('slug', ADMIN_SLUG)->value('content');

    // raymednexus.com does not resolve, so the Rs 100,000 figure the brief
    // took from Indeed has no employer behind it to verify against.
    foreach (['RayMed', 'Rs 100,000', '100,000', 'Indeed'] as $rotten) {
        expect($content)->not->toContain($rotten);
    }
});

it('separates the public and private hiring markets', function () {
    $content = Blog::where('slug', ADMIN_SLUG)->value('content');

    expect($content)->toContain('MBBS')
        ->toContain('postgraduate qualification in hospital administration')
        ->toContain('Business Administration')
        // The education line is what tells a reader which market they are in.
        ->toContain('Read the education requirement before anything else');
});

it('points at the official portals rather than a job board', function () {
    $content = Blog::where('slug', ADMIN_SLUG)->value('content');

    expect($content)->toContain('ulh.org.pk/careers/')
        ->toContain('njp.gov.pk')
        ->toContain('nhsrc.gov.pk')
        ->toContain('Hospital Administrator among its current openings');
});

it('links no vacancy by job identifier', function () {
    $content = Blog::where('slug', ADMIN_SLUG)->value('content');

    expect($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i');
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'Healthcare Administrator%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('creates one listing and repairs the post instead of duplicating it', function () {
    Blog::where('slug', ADMIN_SLUG)->update(['content' => 'stale copy']);

    $this->seed(HealthcareAdministratorJobsPakistanBlogSeeder::class);

    expect(Job::where('position', 'like', 'Healthcare Administrator%')->count())->toBe(1)
        ->and(Blog::where('slug', ADMIN_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', ADMIN_SLUG)->value('content'))->not->toBe('stale copy');
});

it('links to and from the guides it sits between', function () {
    $this->seed(MedicalBillingAssistantJobsPakistanBlogSeeder::class);
    $this->seed(HomeHealthcareAssistantJobsPakistanBlogSeeder::class);
    $this->seed(CallCenterJobsPakistanBlogSeeder::class);
    $this->seed(MedicalReceptionistJobsUkBlogSeeder::class);

    $admin = Blog::where('slug', ADMIN_SLUG)->value('content');

    foreach ([
        'medical-billing-assistant-jobs-in-pakistan',
        'home-healthcare-assistant-jobs-in-pakistan',
        'call-center-jobs-in-pakistan',
        'medical-receptionist-jobs-in-the-uk',
    ] as $slug) {
        expect($admin)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.ADMIN_SLUG);
    }
});
