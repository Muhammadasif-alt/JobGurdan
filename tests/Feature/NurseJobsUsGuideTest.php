<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\CaregiverUkBlogSeeder;
use Database\Seeders\NurseJobsUsBlogSeeder;

use function Pest\Laravel\get;

const NURSE_SLUG = 'nurse-jobs-in-the-us';

const NURSE_APPLY_URL = 'https://www.indeed.com/q-registered-nurse-jobs.html';

beforeEach(function () {
    $this->seed(NurseJobsUsBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', NURSE_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/nurse-jobs-in-us.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', NURSE_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('leads with the visa queue rather than the licensing checklist', function () {
    // The draft listed NCLEX, English and VisaScreen and stopped. The step that
    // decides the timeline is EB-3 retrogression, and staff RN posts do not
    // generally qualify for H-1B at all.
    $body = Blog::where('slug', NURSE_SLUG)->value('content');

    $visaPosition = strpos($body, 'The Visa Reality Nobody Puts First');
    $licensingPosition = strpos($body, 'Licensing: NCLEX, State Boards and VisaScreen');

    expect($visaPosition)->not->toBeFalse()
        ->and($visaPosition)->toBeLessThan($licensingPosition);

    expect($body)->toContain('does not usually qualify for an H-1B visa')
        ->toContain('EB-3 is retrogressed')
        ->toContain('Schedule A');
});

it('says licensing is per state rather than national', function () {
    $response = get('/blog/'.NURSE_SLUG)->assertOk();

    $response->assertSee('There is no single US nursing licence')
        ->assertSee('state Board of Nursing');
});

it('sets out what to check in a recruitment agency contract', function () {
    $body = Blog::where('slug', NURSE_SLUG)->value('content');

    expect($body)->toContain('early-exit figure')
        ->toContain('never pay a placement fee');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', NURSE_SLUG)->value('content'));

    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))->not->toContain('EB-3 nurse green card');

    get('/blog/'.NURSE_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    expect(app(StructuredDataService::class)->describesSingleVacancy(NURSE_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.NURSE_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one United States listing and does not duplicate it on a re-run', function () {
    $this->seed(NurseJobsUsBlogSeeder::class);

    $jobs = Job::where('application_url', NURSE_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', NURSE_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('United States')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('state Board of Nursing');
});

it('links to the other sponsorship guides', function () {
    $this->seed(CaregiverUkBlogSeeder::class);

    get('/blog/'.NURSE_SLUG)->assertOk()
        ->assertSee('/blog/caregiver-jobs-in-uk-with-visa-sponsorship', false)
        ->assertSee('/blog/hotel-jobs-in-usa-for-foreigners', false);
});
