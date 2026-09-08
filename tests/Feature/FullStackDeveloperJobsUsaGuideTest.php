<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\FullStackDeveloperJobsUsaBlogSeeder;
use Database\Seeders\SoftwareDeveloperJobsUsaBlogSeeder;
use Database\Seeders\WebDeveloperJobsUsaBlogSeeder;

use function Pest\Laravel\get;

const FULLSTACK_SLUG = 'full-stack-developer-jobs-in-usa';

const FULLSTACK_APPLY_URL = 'https://www.indeed.com/q-full-stack-developer-jobs.html';

beforeEach(function () {
    $this->seed(FullStackDeveloperJobsUsaBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', FULLSTACK_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/full-stack-developer-jobs-in-usa.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        // blogs.excerpt is a varchar(255); sqlite accepts more, MySQL does not.
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', FULLSTACK_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->toHaveCount(2);

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('explains that the title spans two occupations rather than picking one', function () {
    // This is what keeps the page from competing with the web developer and
    // software developer guides: it is about which band a role belongs to.
    $body = Blog::where('slug', FULLSTACK_SLUG)->value('content');

    expect($body)->toContain('$92,650')
        ->toContain('$135,980')
        ->toContain('$43,000 of median separates them')
        ->toContain('read the balance of the job description rather than the title');
});

it('raises the senior ceiling the draft stopped short of', function () {
    $body = Blog::where('slug', FULLSTACK_SLUG)->value('content');

    expect($body)->toContain('$214,670')
        ->toContain('most guides stop at $180,000');
});

it('corrects the promise of US pay for work done abroad', function () {
    // The draft promised "US-level pay without needing visa sponsorship".
    $response = get('/blog/'.FULLSTACK_SLUG)->assertOk();

    $response->assertSee('Pay is almost always localised', false)
        ->assertSee('benchmark the offer to the market where', false);
});

it('covers employer of record against contractor and the misclassification test', function () {
    $body = Blog::where('slug', FULLSTACK_SLUG)->value('content');

    expect($body)->toContain('employer of record')
        ->toContain('the labour law of the country you live in, not US law')
        // The tests local authorities actually apply.
        ->toContain('how much control the company has over you');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', FULLSTACK_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('MERN stack developer jobs USA');

    get('/blog/'.FULLSTACK_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    // The draft's link carried a ?vjk= copied from the software developer
    // draft, pointing at an unrelated preview; it is stripped.
    expect(app(StructuredDataService::class)->describesSingleVacancy(FULLSTACK_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.FULLSTACK_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one US listing and does not duplicate it on a re-run', function () {
    $this->seed(FullStackDeveloperJobsUsaBlogSeeder::class);

    $jobs = Job::where('application_url', FULLSTACK_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', FULLSTACK_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('United States')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('Full stack is not a tracked occupation');
});

it('bridges the two engineering guides it sits between', function () {
    $this->seed(WebDeveloperJobsUsaBlogSeeder::class);
    $this->seed(SoftwareDeveloperJobsUsaBlogSeeder::class);

    get('/blog/'.FULLSTACK_SLUG)->assertOk()
        ->assertSee('/blog/web-developer-jobs-in-usa', false)
        ->assertSee('/blog/software-developer-jobs-in-usa', false)
        ->assertSee('/blog/remote-jobs-in-pakistan-with-no-experience', false);
});
