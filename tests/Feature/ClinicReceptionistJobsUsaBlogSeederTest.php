<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\AdministrativeAssistantJobsUsaBlogSeeder;
use Database\Seeders\ClinicReceptionistJobsUsaBlogSeeder;
use Database\Seeders\EntryLevelHealthcareJobsBlogSeeder;
use Database\Seeders\MedicalAssistantJobsUsaBlogSeeder;
use Database\Seeders\MedicalRecordsClerkJobsUsaBlogSeeder;

const CLINIC_SLUG = 'clinic-receptionist-jobs-in-usa';

const CLINIC_JOB_URL = '/jobs/clinic-receptionist-medical-front-desk-us-employers-united-states';

beforeEach(function () {
    $this->seed(ClinicReceptionistJobsUsaBlogSeeder::class);
});

it('publishes the clinic receptionist guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', CLINIC_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/clinic-receptionist-jobs-usa.jpg')
        ->and($blog->content)->toContain('clinic-receptionist-jobs-usa-overview.jpg')
        ->and($blog->content)->toContain('clinic-receptionist-jobs-usa-guide.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('keeps the fields Google reads to ASCII', function () {
    $blog = Blog::where('slug', CLINIC_SLUG)->first();

    foreach ([$blog->meta_title, $blog->meta_description, $blog->tags] as $field) {
        expect(mb_check_encoding($field, 'ASCII'))->toBeTrue($field);
    }
});

it('ships every image it references, and none of them is a heavyweight PNG', function () {
    $blog = Blog::where('slug', CLINIC_SLUG)->first();

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
    $this->get('/blog/'.CLINIC_SLUG)
        ->assertOk()
        ->assertSee('Clinic Receptionist Jobs in USA')
        ->assertSee('People Also Search For')
        ->assertSee('bls.gov/ooh/office-and-administrative-support/receptionists.htm');
});

it('carries eight FAQs in markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', CLINIC_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Clinic receptionist salary');

    $this->get('/blog/'.CLINIC_SLUG)->assertSee('"FAQPage"', false);
});

it('offers eight People Also Search For entries', function () {
    $content = Blog::where('slug', CLINIC_SLUG)->value('content');
    $tail = substr($content, strpos($content, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'Related career guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);
});

it('sends nobody to a job aggregator, in the guide or on the listing', function () {
    $content = Blog::where('slug', CLINIC_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Clinic Receptionist%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'simplyhired'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url.' '.$job->description))->not->toContain($aggregator);
    }

    expect($job->application_url)->toBe('https://www.kaiserpermanentejobs.org/');
});

it('carries the BLS figures that were verified, not the ones first published', function () {
    $content = Blog::where('slug', CLINIC_SLUG)->value('content');

    expect($content)->toContain('$18.27')
        ->toContain('$13.83')
        ->toContain('$24.01')
        ->toContain('$38,010')
        ->toContain('105,100')
        ->toContain('May 2025');

    // A "healthcare and social assistance" median BLS does not publish for this
    // occupation, a decline overstated as 2%, and an expiring requisition
    // number quoted with its hourly rate.
    foreach (['$19.00', '2% decline', '1440719', 'requisition number'] as $unverified) {
        expect($content)->not->toContain($unverified);
    }
});

it('quotes the 2025-35 projection exactly as BLS publishes it', function () {
    $content = Blog::where('slug', CLINIC_SLUG)->value('content');

    expect($content)->toContain('1.7 per cent')
        ->toContain('947,500')
        ->toContain('931,600')
        // The all-occupations rate the decline is measured against.
        ->toContain('3.5 per cent');
});

it('separates the pay bands that the single national median hides', function () {
    $content = Blog::where('slug', CLINIC_SLUG)->value('content');

    expect($content)->toContain('$19.54')
        ->toContain('$19.35')
        ->toContain('$19.01')
        ->toContain('$17.23')
        ->toContain('Nursing and residential care facilities')
        ->toContain('high school diploma or equivalent');
});

it('renders the aggregated job overview', function () {
    $this->get(CLINIC_JOB_URL)
        ->assertOk()
        ->assertSee('not an advert for one guaranteed vacancy');
});

it('creates one US listing and repairs the post instead of duplicating it', function () {
    Blog::where('slug', CLINIC_SLUG)->update(['content' => 'stale copy']);

    $this->seed(ClinicReceptionistJobsUsaBlogSeeder::class);

    expect(Job::where('position', 'like', 'Clinic Receptionist%')->count())->toBe(1)
        ->and(Blog::where('slug', CLINIC_SLUG)->count())->toBe(1)
        ->and(Blog::where('slug', CLINIC_SLUG)->value('content'))->not->toBe('stale copy');
});

it('links to and from the guides it sits between', function () {
    $this->seed(MedicalRecordsClerkJobsUsaBlogSeeder::class);
    $this->seed(MedicalAssistantJobsUsaBlogSeeder::class);
    $this->seed(EntryLevelHealthcareJobsBlogSeeder::class);
    $this->seed(AdministrativeAssistantJobsUsaBlogSeeder::class);

    $clinic = Blog::where('slug', CLINIC_SLUG)->value('content');

    foreach ([
        'medical-records-clerk-jobs-in-usa',
        'medical-assistant-jobs-in-usa',
        'entry-level-healthcare-jobs',
        'administrative-assistant-jobs-in-usa',
    ] as $slug) {
        expect($clinic)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.CLINIC_SLUG);
    }
});
