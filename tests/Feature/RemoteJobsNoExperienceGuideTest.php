<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\PrivateJobsFreshGraduatesBlogSeeder;
use Database\Seeders\RemoteJobsNoExperienceBlogSeeder;

use function Pest\Laravel\get;

const REMOTE_SLUG = 'remote-jobs-in-pakistan-with-no-experience';

const REMOTE_APPLY_URL = 'https://pk.indeed.com/q-no-experience-remote-jobs-jobs.html?vjk=2525854d424e204e';

beforeEach(function () {
    $this->seed(RemoteJobsNoExperienceBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', REMOTE_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/remote-jobs-in-pakistan-no-experience.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', REMOTE_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('corrects how payment from abroad actually reaches Pakistan', function () {
    // The draft listed Wise beside Payoneer as an ordinary option. Wise does
    // not give Pakistan-resident accounts multi-currency balances, and PayPal
    // does not operate here at all.
    $response = get('/blog/'.REMOTE_SLUG)->assertOk();

    $response->assertSee('PayPal does not operate for accounts in Pakistan')
        ->assertSee('Payoneer');
});

it('leads with the scam patterns instead of burying them', function () {
    $body = Blog::where('slug', REMOTE_SLUG)->value('content');

    // The draft treated an unpaid onboarding period as routine; that is the
    // most common shape of the fraud this keyword attracts.
    expect($body)->toContain('How to Tell a Real Remote Job From a Scam')
        ->toContain('Unpaid work dressed as training')
        ->not->toContain('paid or unpaid onboarding');

    // The scam section has to come before the role listings, not after.
    $scamPosition = strpos($body, 'How to Tell a Real Remote Job From a Scam');
    $rolesPosition = strpos($body, 'Which Remote Roles Actually Hire Without Experience');

    expect($scamPosition)->toBeLessThan($rolesPosition);
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', REMOTE_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Remote jobs in Pakistan for students');

    get('/blog/'.REMOTE_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    expect(app(StructuredDataService::class)->describesSingleVacancy(REMOTE_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.REMOTE_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one remote Pakistan listing and does not duplicate it on a re-run', function () {
    $this->seed(RemoteJobsNoExperienceBlogSeeder::class);

    $jobs = Job::where('application_url', REMOTE_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', REMOTE_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('Pakistan')
        ->and($jobs->first()->job_type)->toBe('Remote')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('PayPal does not operate');
});

it('links to the fresh graduate guide', function () {
    $this->seed(PrivateJobsFreshGraduatesBlogSeeder::class);

    get('/blog/'.REMOTE_SLUG)->assertOk()
        ->assertSee('/blog/private-jobs-in-pakistan-for-fresh-graduates', false)
        ->assertSee('/blog/government-jobs-in-pakistan', false);
});
