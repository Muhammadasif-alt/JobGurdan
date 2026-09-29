<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\DisabilitySupportWorkerJobsPakistanBlogSeeder;
use Database\Seeders\ElderlyCareAssistantJobsPakistanBlogSeeder;
use Database\Seeders\HomeHealthcareAssistantJobsPakistanBlogSeeder;
use Database\Seeders\PersonalCareAssistantJobsPakistanBlogSeeder;
use Database\Seeders\ResidentialCareWorkerJobsPakistanBlogSeeder;
use Illuminate\Support\Str;

const RCW_SLUG = 'residential-care-worker-jobs-in-pakistan';

beforeEach(function () {
    $this->seed(ResidentialCareWorkerJobsPakistanBlogSeeder::class);
});

it('publishes the residential care guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', RCW_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/residential-care-worker-jobs-pakistan.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('keeps the fields Google reads to ASCII', function () {
    $blog = Blog::where('slug', RCW_SLUG)->first();

    foreach ([$blog->meta_title, $blog->meta_description, $blog->tags] as $field) {
        expect(mb_check_encoding($field, 'ASCII'))->toBeTrue($field);
    }
});

it('keeps the title safe for the unescaped title yield', function () {
    expect(Blog::where('slug', RCW_SLUG)->value('meta_title'))->not->toMatch('/[&<>"]/');
});

it('ships every image it references, and none of them is a heavyweight PNG', function () {
    $blog = Blog::where('slug', RCW_SLUG)->first();

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
    $this->get('/blog/'.RCW_SLUG)
        ->assertOk()
        ->assertSee('Residential Care Worker Jobs in Pakistan')
        ->assertSee('People Also Search For')
        ->assertSee('cpwb.punjab.gov.pk');
});

it('carries eight FAQs in markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', RCW_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8);

    $this->get('/blog/'.RCW_SLUG)->assertSee('"FAQPage"', false);
});

it('offers eight People Also Search For entries', function () {
    $content = Blog::where('slug', RCW_SLUG)->value('content');
    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);
});

it('sends nobody to a job aggregator, in the guide or on the listing', function () {
    $content = Blog::where('slug', RCW_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Residential Care Worker%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'rozee.pk', 'mustakbil'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url.' '.$job->description))->not->toContain($aggregator);
    }

    expect($job->application_url)->toBe('https://cpwb.punjab.gov.pk/jobs');
});

it('names the statute behind the largest residential employer', function () {
    $content = Blog::where('slug', RCW_SLUG)->value('content');

    // The brief never mentioned that residential child care in Punjab is
    // statutory, which is what separates it from the household care guides.
    expect($content)->toContain('Punjab Destitute and Neglected Children Act 2004')
        ->toContain('Child Protection and Welfare Bureau')
        ->toContain('79,106 children have been admitted')
        ->toContain('cpwb.punjab.gov.pk/jobs');
});

it('explains the hiring route that job boards do not carry', function () {
    $content = Blog::where('slug', RCW_SLUG)->value('content');

    expect($content)->toContain('Punjab Public Service Commission')
        ->toContain('closing date');
});

it('treats night duty as the job rather than as overtime', function () {
    $content = Blog::where('slug', RCW_SLUG)->value('content');

    expect($content)->toContain('Whether sleeping is permitted')
        ->toContain('Who you call in an emergency')
        ->toContain('Transport at shift end');
});

it('quotes no aggregator pay band for this work', function () {
    $content = Blog::where('slug', RCW_SLUG)->value('content');

    // The brief carried Rs 25,000 to Rs 30,000 and "up to Rs 35,000", both
    // republished from a job board.
    expect($content)->not->toContain('25,000')
        ->not->toContain('30,000')
        ->not->toContain('35,000')
        ->toContain('are not republished here');
});

it('keeps the SOS vacancy in the terms its own careers page uses', function () {
    $content = Blog::where('slug', RCW_SLUG)->value('content');

    expect($content)->toContain('Assistant Director (Trainee) Residential')
        ->toContain('jobs@sos.org.pk')
        ->toContain('not the entry point');
});

it('links no vacancy by job identifier', function () {
    $content = Blog::where('slug', RCW_SLUG)->value('content');

    expect($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i');
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'Residential Care Worker%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('creates one listing and repairs the post instead of duplicating it', function () {
    Blog::where('slug', RCW_SLUG)->update(['content' => 'stale copy']);

    $this->seed(ResidentialCareWorkerJobsPakistanBlogSeeder::class);

    expect(Job::where('position', 'like', 'Residential Care Worker%')->count())->toBe(1)
        ->and(Blog::where('slug', RCW_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', RCW_SLUG)->value('content'))->not->toBe('stale copy');
});

it('links to and from the four care guides it sits beside', function () {
    $this->seed(HomeHealthcareAssistantJobsPakistanBlogSeeder::class);
    $this->seed(ElderlyCareAssistantJobsPakistanBlogSeeder::class);
    $this->seed(PersonalCareAssistantJobsPakistanBlogSeeder::class);
    $this->seed(DisabilitySupportWorkerJobsPakistanBlogSeeder::class);

    $rcw = Blog::where('slug', RCW_SLUG)->value('content');

    foreach ([
        'home-healthcare-assistant-jobs-in-pakistan',
        'elderly-care-assistant-jobs-in-pakistan',
        'personal-care-assistant-jobs-in-pakistan',
        'disability-support-worker-jobs-in-pakistan',
    ] as $slug) {
        expect($rcw)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.RCW_SLUG);
    }
});
