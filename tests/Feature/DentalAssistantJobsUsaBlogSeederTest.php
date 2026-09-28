<?php

use App\Models\Blog;
use App\Models\Job;
use Database\Seeders\DentalAssistantJobsCanadaBlogSeeder;
use Database\Seeders\DentalAssistantJobsUsaBlogSeeder;

const DENTAL_USA_SLUG = 'dental-assistant-jobs-in-usa';

beforeEach(function () {
    $this->seed(DentalAssistantJobsUsaBlogSeeder::class);
});

it('publishes the dental assistant guide with its images and SEO fields', function () {
    $blog = Blog::where('slug', DENTAL_USA_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/dental-assistant-jobs-in-usa.jpg')
        ->and($blog->content)->toContain('dental-assistant-jobs-in-usa-chairside.jpg')
        ->and($blog->content)->toContain('dental-assistant-jobs-in-usa-credentials.jpg')
        ->and(strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(strlen($blog->meta_description))->toBeLessThanOrEqual(160)
        ->and(strlen($blog->excerpt))->toBeLessThanOrEqual(255);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', DENTAL_USA_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[a-z0-9-]+\.jpg)#', $blog->content, $inline);

    foreach (array_merge([$blog->featured_image], $inline[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue($path.' is missing');
    }
});

it('renders with its long-tail sections', function () {
    $this->get('/blog/'.DENTAL_USA_SLUG)
        ->assertOk()
        ->assertSee('Dental Assistant Jobs in USA')
        ->assertSee('People Also Search For')
        ->assertSee('danb.org/state-requirements');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(App\Services\StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', DENTAL_USA_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Dental assistant state requirements');

    $this->get('/blog/'.DENTAL_USA_SLUG)->assertSee('"FAQPage"', false);
});

it('sends nobody to a job aggregator, in the guide or on the listing', function () {
    // Every "Apply Now" in the draft pointed at Indeed, and one pay figure
    // was lifted from an Indeed listing.
    $content = Blog::where('slug', DENTAL_USA_SLUG)->value('content');
    $job = Job::where('position', 'like', 'Dental Assistant%US Employers')->first();

    foreach (['indeed.com', 'ziprecruiter', 'glassdoor', 'linkedin.com', 'simplyhired'] as $aggregator) {
        expect(strtolower($content))->not->toContain($aggregator)
            ->and(strtolower($job->application_url.' '.$job->description))->not->toContain($aggregator);
    }

    expect($job->application_url)->toStartWith('https://www.usajobs.gov/');
});

it('drops the career centre the draft named, because it does not exist', function () {
    // jobs.adaausa.org does not resolve, and the ADAA's own site is a
    // temporary one under construction with no job board behind it.
    $content = Blog::where('slug', DENTAL_USA_SLUG)->value('content');

    expect($content)->not->toContain('adaausa.org')
        ->and($content)->not->toContain('ADAA Career Center');
});

it('keeps the BLS figures the draft got right', function () {
    $content = Blog::where('slug', DENTAL_USA_SLUG)->value('content');

    expect($content)->toContain('$48,070')
        ->toContain('7 per cent from 2025 to 2035')
        ->toContain('53,000')
        ->toContain('much faster than the average');
});

it('answers the certification question with the state board, not a shrug', function () {
    // The draft stops at "it depends on your state". The body that decides
    // is the state dental board, and DANB publishes all fifty-one of them.
    $content = Blog::where('slug', DENTAL_USA_SLUG)->value('content');

    expect($content)->toContain('postsecondary nondegree award')
        ->toContain('state dental board')
        ->toContain('50 states and the District of Columbia')
        // The route for someone who cannot stop working to study.
        ->toContain('3,500 hours')
        ->toContain('Radiation Health and Safety');
});

it('prices the hygienist comparison instead of hand-waving at it', function () {
    $content = Blog::where('slug', DENTAL_USA_SLUG)->value('content');

    expect($content)->toContain('$98,100')
        ->toContain("Associate's degree")
        ->toContain('Required in every state');
});

it('creates one US listing and does not duplicate it on a re-run', function () {
    $this->seed(DentalAssistantJobsUsaBlogSeeder::class);

    expect(Job::where('position', 'like', 'Dental Assistant%US Employers')->count())->toBe(1)
        ->and(Blog::where('slug', DENTAL_USA_SLUG)->count())->toBe(1);
});

it('links to and from the Canadian guide it is the counterpart to', function () {
    $this->seed(DentalAssistantJobsCanadaBlogSeeder::class);

    $usa = Blog::where('slug', DENTAL_USA_SLUG)->value('content');
    $canada = Blog::where('slug', 'dental-assistant-jobs-in-canada')->value('content');

    expect($usa)->toContain('/blog/dental-assistant-jobs-in-canada')
        ->and($canada)->toContain('/blog/dental-assistant-jobs-in-usa');
});
