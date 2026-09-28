<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\AdministrativeAssistantJobsUsaBlogSeeder;
use Database\Seeders\EntryLevelHealthcareJobsBlogSeeder;
use Database\Seeders\MedicalAssistantJobsUsaBlogSeeder;
use Database\Seeders\MedicalRecordsClerkJobsUsaBlogSeeder;

const RECORDS_SLUG = 'medical-records-clerk-jobs-in-usa';

beforeEach(function () {
    $this->seed(MedicalRecordsClerkJobsUsaBlogSeeder::class);
});

it('publishes the medical records guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', RECORDS_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/medical-records-clerk-jobs-in-usa.jpg')
        ->and($blog->content)->toContain('medical-records-clerk-jobs-in-usa-overview.jpg')
        ->and($blog->content)->toContain('medical-records-clerk-jobs-in-usa-guide.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('keeps the fields Google reads to ASCII', function () {
    $blog = Blog::where('slug', RECORDS_SLUG)->first();

    foreach ([$blog->meta_title, $blog->meta_description, $blog->tags] as $field) {
        expect(mb_check_encoding($field, 'ASCII'))->toBeTrue($field);
    }
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', RECORDS_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[a-z0-9-]+\.jpg)#', $blog->content, $inline);

    foreach (array_merge([$blog->featured_image], $inline[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue($path.' is missing');
    }
});

it('renders with its long-tail sections', function () {
    $this->get('/blog/'.RECORDS_SLUG)
        ->assertOk()
        ->assertSee('Medical Records Clerk Jobs in USA')
        ->assertSee('People Also Search For')
        ->assertSee('ahima.org/certification-careers');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(App\Services\StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', RECORDS_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Medical records clerk salary');

    $this->get('/blog/'.RECORDS_SLUG)->assertSee('"FAQPage"', false);
});

it('sends nobody to a job aggregator, in the guide or on the listing', function () {
    // Every "Apply Now" in the brief pointed at Indeed, two of the three
    // supplied images carried an Indeed strip, and the demand figure was an
    // Indeed listing count.
    $content = Blog::where('slug', RECORDS_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Medical Records Clerk%')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'simplyhired'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url.' '.$job->description))->not->toContain($aggregator);
    }

    expect($job->application_url)->toStartWith('https://www.usajobs.gov/');
});

it('carries the May 2025 figures, not the May 2024 ones the brief used', function () {
    $content = Blog::where('slug', RECORDS_SLUG)->value('content');

    expect($content)->toContain('$51,140')
        ->toContain('$37,000')
        ->toContain('$81,150')
        ->toContain('$59,120')
        ->toContain('$47,120')
        ->toContain('May 2025');

    foreach (['$50,250', '$35,780', '$80,950', '$56,520', '$45,620', '2024 to 2034', '14,200'] as $stale) {
        expect($content)->not->toContain($stale);
    }
});

it('quotes the 2025-35 outlook rather than the previous cycle', function () {
    $content = Blog::where('slug', RECORDS_SLUG)->value('content');

    expect($content)->toContain('8 per cent from 2025 to 2035')
        ->toContain('much faster than the average')
        ->toContain('14,000')
        // The all-occupations rate, which is what "much faster" is measured against.
        ->toContain('3.5 per cent');
});

it('separates the filing job from the records job the pay figure belongs to', function () {
    // The brief answers "high school diploma or GED" and then quotes the
    // median of an occupation BLS records as needing a certificate.
    $content = Blog::where('slug', RECORDS_SLUG)->value('content');

    expect($content)->toContain('postsecondary nondegree award')
        ->toContain('File clerks')
        ->toContain('$43,600')
        ->toContain('15.8%')
        ->toContain('Health information technologists')
        ->toContain('$68,020');
});

it('names the credential that a high school diploma actually opens', function () {
    $content = Blog::where('slug', RECORDS_SLUG)->value('content');

    expect($content)->toContain('Certified Coding Associate')
        ->toContain('$199 for AHIMA members')
        ->toContain('105 questions')
        ->toContain('Registered Health Information Technician')
        ->toContain('CAHIIM');
});

it('creates one US listing and does not duplicate it on a re-run', function () {
    $this->seed(MedicalRecordsClerkJobsUsaBlogSeeder::class);

    expect(Job::where('position', 'like', 'Medical Records Clerk%')->count())->toBe(1)
        ->and(Blog::where('slug', RECORDS_SLUG)->count())->toBe(1);
});

it('links to and from the guides it sits between', function () {
    $this->seed(MedicalAssistantJobsUsaBlogSeeder::class);
    $this->seed(EntryLevelHealthcareJobsBlogSeeder::class);
    $this->seed(AdministrativeAssistantJobsUsaBlogSeeder::class);

    $records = Blog::where('slug', RECORDS_SLUG)->value('content');

    foreach (['medical-assistant-jobs-in-usa', 'entry-level-healthcare-jobs', 'administrative-assistant-jobs-in-usa'] as $slug) {
        expect($records)->toContain('/blog/'.$slug)
            ->and(Blog::where('slug', $slug)->value('content'))->toContain('/blog/'.RECORDS_SLUG);
    }
});
