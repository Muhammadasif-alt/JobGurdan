<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\JavaDeveloperJobsUsaBlogSeeder;
use Database\Seeders\PythonDeveloperJobsUsaBlogSeeder;
use Database\Seeders\WebDeveloperJobsUsaBlogSeeder;

use function Pest\Laravel\get;

const PYTHON_SLUG = 'python-developer-jobs-in-usa';

const PYTHON_APPLY_URL = 'https://www.indeed.com/q-python-developer-jobs.html';

beforeEach(function () {
    $this->seed(PythonDeveloperJobsUsaBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', PYTHON_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/python-developer-jobs-in-usa.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        // blogs.excerpt is a varchar(255); sqlite accepts more, MySQL does not.
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', PYTHON_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->toHaveCount(2);

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('sends readers to a working search rather than the draft host-less link', function () {
    $blog = Blog::where('slug', PYTHON_SLUG)->first();

    expect($blog->content)->toContain(PYTHON_APPLY_URL)
        ->and($blog->content)->not->toContain('https://indeed.com/')
        ->and($blog->content)->not->toContain('vjk=');
});

it('splits the title across the three occupations that actually pay it', function () {
    // The page exists to answer "which occupation is this job in", which is
    // what stops it competing with the other engineering guides.
    $body = Blog::where('slug', PYTHON_SLUG)->value('content');

    expect($body)->toContain('$92,650')
        ->toContain('$120,230')
        ->toContain('$135,980')
        ->toContain('$43,330 of median between the bottom and the top of the same job title');
});

it('carries the data scientist distribution, not just the median', function () {
    $body = Blog::where('slug', PYTHON_SLUG)->value('content');

    expect($body)->toContain('$67,240')
        ->toContain('$199,130')
        ->toContain('$48,100');
});

it('corrects the draft claim that AI and ML Python roles typically pay $150,000', function () {
    // The draft said "often $150,000+". That sits above the $120,230 data
    // scientist median and short of the $199,130 top tenth.
    $response = get('/blog/'.PYTHON_SLUG)->assertOk();

    $response->assertSee('it is not the typical figure', false)
        ->assertSee('well above the data scientist median', false);
});

it('contrasts the two growth projections that make the domain choice matter', function () {
    $body = Blog::where('slug', PYTHON_SLUG)->value('content');

    expect($body)->toContain('grow 35 per cent between 2025 and 2035')
        ->toContain('10 per cent for software developers');
});

it('tells readers to price entry offers against the real floor', function () {
    $body = Blog::where('slug', PYTHON_SLUG)->value('content');

    expect($body)->toContain('first Python offers in the fifties are ordinary rather than an insult');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', PYTHON_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Django developer jobs');

    get('/blog/'.PYTHON_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    expect(app(StructuredDataService::class)->describesSingleVacancy(PYTHON_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.PYTHON_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one US listing and does not duplicate it on a re-run', function () {
    $this->seed(PythonDeveloperJobsUsaBlogSeeder::class);

    $jobs = Job::where('application_url', PYTHON_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', PYTHON_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('United States')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('The domain, not the language, carries the premium');
});

it('links into the engineering cluster in both directions', function () {
    $this->seed(WebDeveloperJobsUsaBlogSeeder::class);
    $this->seed(JavaDeveloperJobsUsaBlogSeeder::class);

    get('/blog/'.PYTHON_SLUG)->assertOk()
        ->assertSee('/blog/software-developer-jobs-in-usa', false)
        ->assertSee('/blog/full-stack-developer-jobs-in-usa', false)
        ->assertSee('/blog/front-end-developer-jobs-in-usa', false)
        ->assertSee('/blog/java-developer-jobs-in-usa', false)
        ->assertSee('/blog/react-developer-jobs-in-usa', false);

    get('/blog/web-developer-jobs-in-usa')->assertOk()
        ->assertSee('/blog/'.PYTHON_SLUG, false);

    get('/blog/java-developer-jobs-in-usa')->assertOk()
        ->assertSee('/blog/'.PYTHON_SLUG, false);
});
