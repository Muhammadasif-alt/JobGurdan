<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\HealthcareAdministratorJobsPakistanBlogSeeder;
use Database\Seeders\HomeHealthcareAssistantJobsPakistanBlogSeeder;
use Database\Seeders\MedicalBillingAssistantJobsPakistanBlogSeeder;
use Database\Seeders\MedicalRecordsClerkJobsUsaBlogSeeder;
use Database\Seeders\VirtualAssistantJobsPakistanBlogSeeder;
use Illuminate\Support\Str;

const BILLING_SLUG = 'medical-billing-assistant-jobs-in-pakistan';

beforeEach(function () {
    $this->seed(MedicalBillingAssistantJobsPakistanBlogSeeder::class);
});

it('publishes the medical billing guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', BILLING_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/medical-billing-assistant-jobs-pakistan.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('keeps the fields Google reads to ASCII', function () {
    $blog = Blog::where('slug', BILLING_SLUG)->first();

    foreach ([$blog->meta_title, $blog->meta_description, $blog->tags] as $field) {
        expect(mb_check_encoding($field, 'ASCII'))->toBeTrue($field);
    }
});

it('ships every image it references, and none of them is a heavyweight PNG', function () {
    $blog = Blog::where('slug', BILLING_SLUG)->first();

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
    $this->get('/blog/'.BILLING_SLUG)
        ->assertOk()
        ->assertSee('Medical Billing Assistant Jobs in Pakistan')
        ->assertSee('People Also Search For')
        ->assertSee('labour.punjab.gov.pk/minimum-wages-notification');
});

it('carries eight FAQs in markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', BILLING_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8);

    $this->get('/blog/'.BILLING_SLUG)->assertSee('"FAQPage"', false);
});

it('offers eight People Also Search For entries', function () {
    $content = Blog::where('slug', BILLING_SLUG)->value('content');
    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);
});

it('sends nobody to a job aggregator, in the guide or on the listing', function () {
    $content = Blog::where('slug', BILLING_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Medical Billing Assistant%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'rozee.pk', 'mustakbil'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url.' '.$job->description))->not->toContain($aggregator);
    }

    expect($job->application_url)->toBe('https://ergmd.com/careers');
});

it('prices the fresher benchmark against the statutory minimum wage', function () {
    $content = Blog::where('slug', BILLING_SLUG)->value('content');

    // The employer publishes the band; Punjab notifies the floor. The point
    // of the guide is that the top of the band equals the floor.
    expect($content)->toContain('PKR 30,000 to PKR 40,000')
        ->toContain('PKR 40,000 a month for an unskilled adult worker')
        ->toContain('labour.punjab.gov.pk/minimum-wages-notification')
        ->toContain('The top of the entry band is the legal floor');
});

it('drops the Indeed salary the brief quoted', function () {
    $content = Blog::where('slug', BILLING_SLUG)->value('content');

    // A single aggregator advert for an experienced specialist is not a
    // market rate for a fresher, and is never republished here.
    foreach (['90,000', '120,000', 'Indeed'] as $rotten) {
        expect($content)->not->toContain($rotten);
    }
});

it('prices certification instead of vaguely recommending it', function () {
    $content = Blog::where('slug', BILLING_SLUG)->value('content');

    expect($content)->toContain('USD 425')
        ->toContain('USD 499')
        ->toContain('135 multiple-choice questions')
        ->toContain('aapc.com/certifications/cpb');
});

it('publishes the employer terms it verified, not the ones it assumed', function () {
    $content = Blog::where('slug', BILLING_SLUG)->value('content');

    expect($content)->toContain('Gulberg III')
        ->toContain('6:00 PM to 3:00 AM')
        ->toContain('ergmd.com/careers')
        ->toContain('ergmd.com/apply');
});

it('links no vacancy by job identifier', function () {
    $content = Blog::where('slug', BILLING_SLUG)->value('content');

    expect($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i');
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'Medical Billing Assistant%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('creates one listing and repairs the post instead of duplicating it', function () {
    Blog::where('slug', BILLING_SLUG)->update(['content' => 'stale copy']);

    $this->seed(MedicalBillingAssistantJobsPakistanBlogSeeder::class);

    expect(Job::where('position', 'like', 'Medical Billing Assistant%')->count())->toBe(1)
        ->and(Blog::where('slug', BILLING_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', BILLING_SLUG)->value('content'))->not->toBe('stale copy');
});

it('links to and from the guides it sits between', function () {
    $this->seed(HealthcareAdministratorJobsPakistanBlogSeeder::class);
    $this->seed(HomeHealthcareAssistantJobsPakistanBlogSeeder::class);
    $this->seed(VirtualAssistantJobsPakistanBlogSeeder::class);
    $this->seed(MedicalRecordsClerkJobsUsaBlogSeeder::class);

    $billing = Blog::where('slug', BILLING_SLUG)->value('content');

    foreach ([
        'healthcare-administrator-jobs-in-pakistan',
        'home-healthcare-assistant-jobs-in-pakistan',
        'virtual-assistant-jobs-in-pakistan',
        'medical-records-clerk-jobs-in-usa',
    ] as $slug) {
        expect($billing)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.BILLING_SLUG);
    }
});
