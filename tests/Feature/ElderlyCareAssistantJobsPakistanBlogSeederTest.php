<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\DisabilitySupportWorkerJobsPakistanBlogSeeder;
use Database\Seeders\ElderlyCareAssistantJobsPakistanBlogSeeder;
use Database\Seeders\HealthcareAdministratorJobsPakistanBlogSeeder;
use Database\Seeders\HomeHealthcareAssistantJobsPakistanBlogSeeder;
use Database\Seeders\PersonalCareAssistantJobsPakistanBlogSeeder;
use Illuminate\Support\Str;

const ELDERCARE_SLUG = 'elderly-care-assistant-jobs-in-pakistan';

beforeEach(function () {
    $this->seed(ElderlyCareAssistantJobsPakistanBlogSeeder::class);
});

it('publishes the elderly care guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', ELDERCARE_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/elderly-care-assistant-jobs-pakistan.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('keeps the fields Google reads to ASCII', function () {
    $blog = Blog::where('slug', ELDERCARE_SLUG)->first();

    foreach ([$blog->meta_title, $blog->meta_description, $blog->tags] as $field) {
        expect(mb_check_encoding($field, 'ASCII'))->toBeTrue($field);
    }
});

it('ships every image it references, and none of them is a heavyweight PNG', function () {
    $blog = Blog::where('slug', ELDERCARE_SLUG)->first();

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
    $this->get('/blog/'.ELDERCARE_SLUG)
        ->assertOk()
        ->assertSee('Elderly Care Assistant Jobs in Pakistan')
        ->assertSee('People Also Search For')
        ->assertSee('navttc.gov.pk');
});

it('carries eight FAQs in markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', ELDERCARE_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8);

    $this->get('/blog/'.ELDERCARE_SLUG)->assertSee('"FAQPage"', false);
});

it('offers eight People Also Search For entries', function () {
    $content = Blog::where('slug', ELDERCARE_SLUG)->value('content');
    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);
});

it('sends nobody to a job aggregator, in the guide or on the listing', function () {
    $content = Blog::where('slug', ELDERCARE_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Elderly Care Assistant%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'rozee.pk', 'mustakbil'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url.' '.$job->description))->not->toContain($aggregator);
    }

    expect($job->application_url)->toBe('https://carenest.pk/careers.html');
});

it('names the government qualification the brief only alluded to', function () {
    $content = Blog::where('slug', ELDERCARE_SLUG)->value('content');

    // The spine of the guide: a free, state-run qualification almost nobody
    // applying for these jobs has heard of.
    expect($content)->toContain('090921ECG')
        ->toContain('nine weeks')
        ->toContain('Domestic Worker Program')
        ->toContain('navttc.gov.pk/qualifications/domestic-worker-3-elderly-care-giver');
});

it('refuses to quote a dementia prevalence figure it cannot stand behind', function () {
    $content = Blog::where('slug', ELDERCARE_SLUG)->value('content');

    // Published Pakistani estimates range from the low hundreds of thousands
    // to over a million, and the researchers say so themselves.
    expect($content)->toContain('Reliable national figures')
        ->toContain('lack of study rather than genuine disagreement');
});

it('drops the aggregator salary bands the brief quoted', function () {
    $content = Blog::where('slug', ELDERCARE_SLUG)->value('content');

    foreach (['40,000', '90,000', '25,000', '30,000', 'Indeed'] as $rotten) {
        expect($content)->not->toContain($rotten);
    }
});

it('defers the clinical line to the guide that argues it', function () {
    $content = Blog::where('slug', ELDERCARE_SLUG)->value('content');

    // Restating the PNMC register here would put the two pages in competition.
    expect($content)->toContain('/blog/home-healthcare-assistant-jobs-in-pakistan')
        ->and($content)->not->toContain('Pakistan has no CNA licence')
        ->and($content)->not->toContain('pnmc.gov.pk');
});

it('links no vacancy by job identifier', function () {
    $content = Blog::where('slug', ELDERCARE_SLUG)->value('content');

    expect($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i');
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'Elderly Care Assistant%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('creates one listing and repairs the post instead of duplicating it', function () {
    Blog::where('slug', ELDERCARE_SLUG)->update(['content' => 'stale copy']);

    $this->seed(ElderlyCareAssistantJobsPakistanBlogSeeder::class);

    expect(Job::where('position', 'like', 'Elderly Care Assistant%')->count())->toBe(1)
        ->and(Blog::where('slug', ELDERCARE_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', ELDERCARE_SLUG)->value('content'))->not->toBe('stale copy');
});

it('links to and from the guides it sits between', function () {
    $this->seed(HomeHealthcareAssistantJobsPakistanBlogSeeder::class);
    $this->seed(PersonalCareAssistantJobsPakistanBlogSeeder::class);
    $this->seed(DisabilitySupportWorkerJobsPakistanBlogSeeder::class);
    $this->seed(HealthcareAdministratorJobsPakistanBlogSeeder::class);

    $eldercare = Blog::where('slug', ELDERCARE_SLUG)->value('content');

    foreach ([
        'home-healthcare-assistant-jobs-in-pakistan',
        'personal-care-assistant-jobs-in-pakistan',
        'disability-support-worker-jobs-in-pakistan',
        'healthcare-administrator-jobs-in-pakistan',
    ] as $slug) {
        expect($eldercare)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.ELDERCARE_SLUG);
    }
});
