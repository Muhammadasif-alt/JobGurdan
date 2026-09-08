<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\FrontEndDeveloperJobsUsaBlogSeeder;
use Database\Seeders\ReactDeveloperJobsUsaBlogSeeder;

use function Pest\Laravel\get;

const REACT_SLUG = 'react-developer-jobs-in-usa';

const REACT_APPLY_URL = 'https://www.indeed.com/q-react-developer-jobs.html';

beforeEach(function () {
    $this->seed(ReactDeveloperJobsUsaBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', REACT_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/react-developer-jobs-in-usa.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        // blogs.excerpt is a varchar(255); sqlite accepts more, MySQL does not.
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', REACT_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->toHaveCount(2);

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('strips the search-preview parameter the draft apply link carried', function () {
    // The draft link was ?vjk=5d0204c8ca5dabd6, which previews one unrelated
    // listing rather than the search.
    $blog = Blog::where('slug', REACT_SLUG)->first();

    expect($blog->content)->toContain(REACT_APPLY_URL)
        ->and($blog->content)->not->toContain('vjk=');
});

it('anchors pay on both occupations a React role can belong to', function () {
    $body = Blog::where('slug', REACT_SLUG)->value('content');

    expect($body)->toContain('$92,650')
        ->toContain('$48,100')
        ->toContain('$162,290')
        ->toContain('$135,980');
});

it('carries the toolchain change that dates a portfolio', function () {
    // The page's differentiator, and absent from the draft: CRA was sunset on
    // 14 February 2025 and React 19 shipped on 5 December 2024.
    $response = get('/blog/'.REACT_SLUG)->assertOk();

    $response->assertSee('officially sunset on 14 February 2025', false)
        ->assertSee('React 19 shipped on 5 December 2024', false)
        ->assertSee('Vite', false)
        ->assertSee('scaffolded with Create React App reads as someone who stopped learning in 2023', false);
});

it('separates commodity React work from work that carries a rate', function () {
    $body = Blog::where('slug', REACT_SLUG)->value('content');

    expect($body)->toContain('the difference is substitutability rather than difficulty')
        ->toContain('the first thing a client shops around on price')
        ->toContain('State architecture');
});

it('defers accessibility and Core Web Vitals to the front end guide', function () {
    // Both are argued at length in the front end guide; repeating them here
    // would put the two pages in competition.
    $body = Blog::where('slug', REACT_SLUG)->value('content');

    expect($body)->toContain('/blog/front-end-developer-jobs-in-usa')
        ->and($body)->not->toContain('26 April 2027')
        ->and($body)->not->toContain('200 milliseconds');
});

it('warns about fixed-price scope before quoting freelance work', function () {
    $body = Blog::where('slug', REACT_SLUG)->value('content');

    expect($body)->toContain('has no defined end')
        ->toContain('Price the states and the revisions, or bill hourly')
        // The tax mechanics live in the web developer guide.
        ->toContain('/blog/web-developer-jobs-in-usa');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', REACT_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Next.js jobs USA');

    get('/blog/'.REACT_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    expect(app(StructuredDataService::class)->describesSingleVacancy(REACT_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.REACT_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one US listing and does not duplicate it on a re-run', function () {
    $this->seed(ReactDeveloperJobsUsaBlogSeeder::class);

    $jobs = Job::where('application_url', REACT_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', REACT_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('United States')
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('Create React App was sunset in February 2025');
});

it('links into the engineering cluster in both directions', function () {
    $this->seed(FrontEndDeveloperJobsUsaBlogSeeder::class);

    get('/blog/'.REACT_SLUG)->assertOk()
        ->assertSee('/blog/front-end-developer-jobs-in-usa', false)
        ->assertSee('/blog/full-stack-developer-jobs-in-usa', false)
        ->assertSee('/blog/python-developer-jobs-in-usa', false)
        ->assertSee('/blog/java-developer-jobs-in-usa', false);

    get('/blog/front-end-developer-jobs-in-usa')->assertOk()
        ->assertSee('/blog/'.REACT_SLUG, false);
});
