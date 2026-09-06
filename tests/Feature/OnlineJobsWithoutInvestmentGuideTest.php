<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\OnlineJobsWithoutInvestmentBlogSeeder;
use Database\Seeders\RemoteJobsNoExperienceBlogSeeder;

use function Pest\Laravel\get;

const NOINVEST_SLUG = 'online-jobs-without-investment-in-pakistan';

const NOINVEST_APPLY_URL = 'https://pk.indeed.com/q-without-investment,-online-jobs.html?vjk=9997e1fc229fd58d';

beforeEach(function () {
    $this->seed(OnlineJobsWithoutInvestmentBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', NOINVEST_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/online-jobs-without-investment-pakistan.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', NOINVEST_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('states what the platforms actually take out of earnings', function () {
    // The draft presented Fiverr and Upwork as costing nothing. Fiverr takes
    // 20% of every order and Upwork sells Connects per proposal, so "without
    // investment" describes the entry rather than the economics.
    $response = get('/blog/'.NOINVEST_SLUG)->assertOk();

    $response->assertSee('20% of every order')
        ->assertSee('Connects')
        ->assertSee('Does Not Mean');
});

it('separates client work from employment and names the wage floor', function () {
    $body = Blog::where('slug', NOINVEST_SLUG)->value('content');

    expect($body)->toContain('Most of This Is Not a Job')
        ->toContain('40,000')
        ->toContain('40,700')
        ->toContain('no minimum wage underneath you');
});

it('calls out daily payment and WhatsApp-only offers as the scam patterns', function () {
    $body = Blog::where('slug', NOINVEST_SLUG)->value('content');

    expect($body)->toContain('clearest scam pattern in this category')
        ->toContain('a message from an unknown number')
        ->toContain('PayPal does not operate for accounts in Pakistan');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', NOINVEST_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Online earning in Pakistan for beginners');

    get('/blog/'.NOINVEST_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    expect(app(StructuredDataService::class)->describesSingleVacancy(NOINVEST_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.NOINVEST_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one Pakistan listing and does not duplicate it on a re-run', function () {
    $this->seed(OnlineJobsWithoutInvestmentBlogSeeder::class);

    $jobs = Job::where('application_url', NOINVEST_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', NOINVEST_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('Pakistan')
        ->and($jobs->first()->job_type)->toBe('Remote')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('20% of every order');
});

it('links to the employed remote guide', function () {
    $this->seed(RemoteJobsNoExperienceBlogSeeder::class);

    get('/blog/'.NOINVEST_SLUG)->assertOk()
        ->assertSee('/blog/remote-jobs-in-pakistan-with-no-experience', false)
        ->assertSee('/blog/remote-data-entry-jobs', false);
});
