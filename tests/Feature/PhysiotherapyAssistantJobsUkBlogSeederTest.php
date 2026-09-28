<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\HealthcareAssistantJobsUkBlogSeeder;
use Database\Seeders\HospitalSupportWorkerJobsUkBlogSeeder;
use Database\Seeders\NursingAssistantJobsUkBlogSeeder;
use Database\Seeders\PhysicalTherapistJobsUsaBlogSeeder;
use Database\Seeders\PhysiotherapyAssistantJobsUkBlogSeeder;
use Illuminate\Support\Str;

const PHYSIO_SLUG = 'physiotherapy-assistant-jobs-in-the-uk';

beforeEach(function () {
    $this->seed(PhysiotherapyAssistantJobsUkBlogSeeder::class);
});

it('publishes the physiotherapy assistant guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', PHYSIO_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/physiotherapy-assistant-jobs-uk.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('keeps the fields Google reads to ASCII', function () {
    $blog = Blog::where('slug', PHYSIO_SLUG)->first();

    foreach ([$blog->meta_title, $blog->meta_description, $blog->tags] as $field) {
        expect(mb_check_encoding($field, 'ASCII'))->toBeTrue($field);
    }
});

it('ships every image it references, and none of them is a heavyweight PNG', function () {
    $blog = Blog::where('slug', PHYSIO_SLUG)->first();

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
    $this->get('/blog/'.PHYSIO_SLUG)
        ->assertOk()
        ->assertSee('Physiotherapy Assistant Jobs in the UK')
        ->assertSee('People Also Search For')
        ->assertSee('nhsemployers.org/articles/pay-scales-202627');
});

it('carries eight FAQs in markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', PHYSIO_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8);

    $this->get('/blog/'.PHYSIO_SLUG)->assertSee('"FAQPage"', false);
});

it('offers eight People Also Search For entries', function () {
    $content = Blog::where('slug', PHYSIO_SLUG)->value('content');
    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);
});

it('sends nobody to a job aggregator, in the guide or on the listing', function () {
    $content = Blog::where('slug', PHYSIO_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Physiotherapy Assistant%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'totaljobs', 'reed.co.uk'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url.' '.$job->description))->not->toContain($aggregator);
    }

    expect($job->application_url)->toBe('https://www.jobs.nhs.uk/');
});

it('publishes the current Agenda for Change rates, not the ones in the brief', function () {
    $content = Blog::where('slug', PHYSIO_SLUG)->value('content');

    // England from 1 April 2026, and Scotland as revised by PCS(AFC)2026/1.
    expect($content)->toContain('&pound;25,272')
        ->toContain('&pound;25,760')
        ->toContain('&pound;27,476')
        ->toContain('&pound;29,103')
        ->toContain('&pound;31,409')
        ->toContain('&pound;12.92');

    // The brief quoted Scotland's withdrawn figures as UK-wide. They survive
    // only inside the section that corrects them, never as an answer.
    $answers = substr($content, strpos($content, 'Frequently Asked Questions'));

    expect($content)->toContain('superseded')
        ->and($answers)->not->toContain('&pound;29,061')
        ->and($answers)->not->toContain('&pound;31,364');
});

it('separates England from Scotland instead of quoting one UK figure', function () {
    $content = Blog::where('slug', PHYSIO_SLUG)->value('content');

    expect($content)->toContain('36-hour week')
        ->toContain('&pound;3,343')
        ->toContain('&pound;3,933')
        // Band 2 is a single point in England, not a range.
        ->toContain('a single point');
});

it('prices the rota, because Band 2 sits just above the legal minimum', function () {
    $content = Blog::where('slug', PHYSIO_SLUG)->value('content');

    expect($content)->toContain('&pound;12.71')
        ->toContain('41 per cent')
        ->toContain('83 per cent')
        ->toContain('&pound;18.22')
        ->toContain('&pound;17.78');
});

it('states the entry requirement the CSP actually publishes', function () {
    $content = Blog::where('slug', PHYSIO_SLUG)->value('content');

    expect($content)->toContain('no national recognised entry qualification')
        ->toContain('16 standards')
        ->toContain('csp.org.uk');
});

it('renders the aggregated job overview', function () {
    $job = Job::where('position', 'like', 'Physiotherapy Assistant%')->with('location')->first();

    $this->get(route('jobs.show', Str::slug($job->position.'-'.$job->location->name)))
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('creates one UK listing and repairs the post instead of duplicating it', function () {
    Blog::where('slug', PHYSIO_SLUG)->update(['content' => 'stale copy']);

    $this->seed(PhysiotherapyAssistantJobsUkBlogSeeder::class);

    expect(Job::where('position', 'like', 'Physiotherapy Assistant%')->count())->toBe(1)
        ->and(Blog::where('slug', PHYSIO_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', PHYSIO_SLUG)->value('content'))->not->toBe('stale copy');
});

it('links to and from the guides it sits between', function () {
    $this->seed(HospitalSupportWorkerJobsUkBlogSeeder::class);
    $this->seed(NursingAssistantJobsUkBlogSeeder::class);
    $this->seed(HealthcareAssistantJobsUkBlogSeeder::class);
    $this->seed(PhysicalTherapistJobsUsaBlogSeeder::class);

    $physio = Blog::where('slug', PHYSIO_SLUG)->value('content');

    foreach ([
        'hospital-support-worker-jobs-in-the-uk',
        'nursing-assistant-jobs-in-the-uk',
        'healthcare-assistant-jobs-in-uk',
        'physical-therapist-jobs-in-usa',
    ] as $slug) {
        expect($physio)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.PHYSIO_SLUG);
    }
});
