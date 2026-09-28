<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\HealthcareAssistantJobsUkBlogSeeder;
use Database\Seeders\HealthcareSupportJobsUkBlogSeeder;
use Database\Seeders\HospitalSupportWorkerJobsUkBlogSeeder;
use Database\Seeders\NursingAssistantJobsUkBlogSeeder;
use Database\Seeders\PhysiotherapyAssistantJobsUkBlogSeeder;
use Illuminate\Support\Str;

const HOSPITAL_SLUG = 'hospital-support-worker-jobs-in-the-uk';

beforeEach(function () {
    $this->seed(HospitalSupportWorkerJobsUkBlogSeeder::class);
});

it('publishes the hospital support worker guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', HOSPITAL_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/hospital-support-worker-jobs-uk.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('keeps the fields Google reads to ASCII', function () {
    $blog = Blog::where('slug', HOSPITAL_SLUG)->first();

    foreach ([$blog->meta_title, $blog->meta_description, $blog->tags] as $field) {
        expect(mb_check_encoding($field, 'ASCII'))->toBeTrue($field);
    }
});

it('ships every image it references, and none of them is a heavyweight PNG', function () {
    $blog = Blog::where('slug', HOSPITAL_SLUG)->first();

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
    $this->get('/blog/'.HOSPITAL_SLUG)
        ->assertOk()
        ->assertSee('Hospital Support Worker Jobs in the UK')
        ->assertSee('People Also Search For')
        ->assertSee('nhsemployers.org/articles/unsocial-hours-payments');
});

it('carries eight FAQs in markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', HOSPITAL_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8);

    $this->get('/blog/'.HOSPITAL_SLUG)->assertSee('"FAQPage"', false);
});

it('offers eight People Also Search For entries', function () {
    $content = Blog::where('slug', HOSPITAL_SLUG)->value('content');
    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);
});

it('sends nobody to a job aggregator, in the guide or on the listing', function () {
    $content = Blog::where('slug', HOSPITAL_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Hospital Support Worker%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'totaljobs', 'reed.co.uk'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url.' '.$job->description))->not->toContain($aggregator);
    }

    expect($job->application_url)->toBe('https://www.jobs.nhs.uk/');
});

it('publishes the current Agenda for Change rates, not the ones in the brief', function () {
    $content = Blog::where('slug', HOSPITAL_SLUG)->value('content');

    expect($content)->toContain('&pound;25,272')
        ->toContain('&pound;25,760')
        ->toContain('&pound;27,476')
        ->toContain('&pound;29,103')
        ->toContain('&pound;31,409');

    // Scotland's withdrawn figure survives only where the guide corrects it.
    $answers = substr($content, strpos($content, 'Frequently Asked Questions'));

    expect($content)->toContain('&pound;3,301')
        ->and($answers)->not->toContain('&pound;29,061')
        ->and($answers)->not->toContain('&pound;31,364');

    // The brief described England's Band 2 as a range. It is one pay point.
    expect($content)->toContain('a single point');
});

it('prices the rota, because Band 2 sits just above the legal minimum', function () {
    $content = Blog::where('slug', HOSPITAL_SLUG)->value('content');

    expect($content)->toContain('&pound;12.71')
        ->toContain('&pound;12.92')
        ->toContain('41 per cent')
        ->toContain('83 per cent')
        ->toContain('&pound;18.22')
        ->toContain('&pound;17.78')
        ->toContain('&pound;23.64');
});

it('answers sponsorship by occupation code rather than by advert', function () {
    $content = Blog::where('slug', HOSPITAL_SLUG)->value('content');

    expect($content)->toContain('6131')
        ->toContain('6135')
        ->toContain('6136')
        ->toContain('22 July 2025')
        ->toContain('gov.uk/health-care-worker-visa');
});

it('drops the single advert figures the brief quoted', function () {
    $content = Blog::where('slug', HOSPITAL_SLUG)->value('content');

    // A lone 2025 charity advert and an Indeed shift-pattern claim rot within
    // a year, so neither is republished here.
    foreach (['13.21', "Indeed's", '267'] as $rotten) {
        expect($content)->not->toContain($rotten);
    }

    expect($content)->toContain('16 standards')
        ->toContain('30 different roles');
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'Hospital Support Worker%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('creates one UK listing and repairs the post instead of duplicating it', function () {
    Blog::where('slug', HOSPITAL_SLUG)->update(['content' => 'stale copy']);

    $this->seed(HospitalSupportWorkerJobsUkBlogSeeder::class);

    expect(Job::where('position', 'like', 'Hospital Support Worker%')->count())->toBe(1)
        ->and(Blog::where('slug', HOSPITAL_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', HOSPITAL_SLUG)->value('content'))->not->toBe('stale copy');
});

it('links to and from the guides it sits between', function () {
    $this->seed(PhysiotherapyAssistantJobsUkBlogSeeder::class);
    $this->seed(HealthcareAssistantJobsUkBlogSeeder::class);
    $this->seed(NursingAssistantJobsUkBlogSeeder::class);
    $this->seed(HealthcareSupportJobsUkBlogSeeder::class);

    $hospital = Blog::where('slug', HOSPITAL_SLUG)->value('content');

    foreach ([
        'physiotherapy-assistant-jobs-in-the-uk',
        'healthcare-assistant-jobs-in-uk',
        'nursing-assistant-jobs-in-the-uk',
        'healthcare-support-jobs-in-uk',
    ] as $slug) {
        expect($hospital)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.HOSPITAL_SLUG);
    }
});
