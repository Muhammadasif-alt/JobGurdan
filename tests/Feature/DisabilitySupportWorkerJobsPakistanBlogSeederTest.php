<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\DisabilitySupportWorkerJobsPakistanBlogSeeder;
use Database\Seeders\ElderlyCareAssistantJobsPakistanBlogSeeder;
use Database\Seeders\HomeHealthcareAssistantJobsPakistanBlogSeeder;
use Database\Seeders\PersonalCareAssistantJobsPakistanBlogSeeder;
use Database\Seeders\TeacherJobsPakistanBlogSeeder;
use Illuminate\Support\Str;

const DSW_SLUG = 'disability-support-worker-jobs-in-pakistan';

beforeEach(function () {
    $this->seed(DisabilitySupportWorkerJobsPakistanBlogSeeder::class);
});

it('publishes the disability support guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', DSW_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/disability-support-worker-jobs-pakistan.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('keeps the fields Google reads to ASCII', function () {
    $blog = Blog::where('slug', DSW_SLUG)->first();

    foreach ([$blog->meta_title, $blog->meta_description, $blog->tags] as $field) {
        expect(mb_check_encoding($field, 'ASCII'))->toBeTrue($field);
    }
});

it('ships every image it references, and none of them is a heavyweight PNG', function () {
    $blog = Blog::where('slug', DSW_SLUG)->first();

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
    $this->get('/blog/'.DSW_SLUG)
        ->assertOk()
        ->assertSee('Disability Support Worker Jobs in Pakistan')
        ->assertSee('People Also Search For')
        ->assertSee('sed.punjab.gov.pk');
});

it('carries eight FAQs in markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', DSW_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8);

    $this->get('/blog/'.DSW_SLUG)->assertSee('"FAQPage"', false);
});

it('offers eight People Also Search For entries', function () {
    $content = Blog::where('slug', DSW_SLUG)->value('content');
    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);
});

it('sends nobody to a job aggregator, in the guide or on the listing', function () {
    $content = Blog::where('slug', DSW_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Disability Support Worker%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'rozee.pk', 'mustakbil'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url.' '.$job->description))->not->toContain($aggregator);
    }

    expect($job->application_url)->toBe('https://sed.punjab.gov.pk/jobs');
});

it('gives the titles the work is really advertised under', function () {
    $content = Blog::where('slug', DSW_SLUG)->value('content');

    // The premise of the guide: searching the English job title returns
    // nothing, so the reader concludes the field is not hiring.
    foreach (['Special Education Teacher', 'Learning Support Assistant', 'Autism Support Assistant', 'Shadow Teacher', 'Therapy Assistant'] as $title) {
        expect($content)->toContain($title);
    }
});

it('carries the statutory quota and the body that publishes it', function () {
    $content = Blog::where('slug', DSW_SLUG)->value('content');

    expect($content)->toContain('Disabled Persons (Employment and Rehabilitation) Ordinance 1981')
        ->toContain('three per cent')
        ->toContain('Disabled Persons Rehabilitation Fund')
        ->toContain('crpd.punjab.gov.pk');
});

it('counts the institutions instead of gesturing at the sector', function () {
    $content = Blog::where('slug', DSW_SLUG)->value('content');

    // The department's own published figures, which the brief replaced with
    // "current 2026 recruitment information".
    expect($content)->toContain('118 specialised institutions')
        ->toContain('45')
        ->toContain('36')
        ->toContain('sed.punjab.gov.pk/jobs');
});

it('does not present the closed NDF vacancy as an opening', function () {
    $content = Blog::where('slug', DSW_SLUG)->value('content');

    // The brief said NDF "maintains an official careers page" with current
    // vacancy information. The vacancy shown there has closed.
    expect($content)->toContain('dated 7 July 2026 and has closed')
        ->toContain('confirm a closing date');
});

it('is honest about what the specialist roles require', function () {
    $content = Blog::where('slug', DSW_SLUG)->value('content');

    expect($content)->toContain('registered behaviour technician training')
        ->toContain('Short online courses do not substitute');
});

it('links no vacancy by job identifier', function () {
    $content = Blog::where('slug', DSW_SLUG)->value('content');

    expect($content)->not->toMatch('/href="[^"]*[?&](jobid|job_id|jobId|vacancyid)=/i');
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'Disability Support Worker%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('creates one listing and repairs the post instead of duplicating it', function () {
    Blog::where('slug', DSW_SLUG)->update(['content' => 'stale copy']);

    $this->seed(DisabilitySupportWorkerJobsPakistanBlogSeeder::class);

    expect(Job::where('position', 'like', 'Disability Support Worker%')->count())->toBe(1)
        ->and(Blog::where('slug', DSW_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', DSW_SLUG)->value('content'))->not->toBe('stale copy');
});

it('links to and from the guides it sits between', function () {
    $this->seed(PersonalCareAssistantJobsPakistanBlogSeeder::class);
    $this->seed(ElderlyCareAssistantJobsPakistanBlogSeeder::class);
    $this->seed(HomeHealthcareAssistantJobsPakistanBlogSeeder::class);
    $this->seed(TeacherJobsPakistanBlogSeeder::class);

    $dsw = Blog::where('slug', DSW_SLUG)->value('content');

    foreach ([
        'personal-care-assistant-jobs-in-pakistan',
        'elderly-care-assistant-jobs-in-pakistan',
        'home-healthcare-assistant-jobs-in-pakistan',
        'teacher-jobs-in-pakistan',
    ] as $slug) {
        expect($dsw)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.DSW_SLUG);
    }
});
