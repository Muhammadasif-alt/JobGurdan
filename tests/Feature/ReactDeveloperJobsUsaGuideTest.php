<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\FrontEndDeveloperJobsUsaBlogSeeder;
use Database\Seeders\ReactDeveloperJobsUsaBlogSeeder;

use function Pest\Laravel\get;

const REACT_SLUG = 'react-developer-jobs-in-usa';

const REACT_APPLY_URL = 'https://www.usa.gov/job-search';

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

it('answers each modifier query from a section of this page rather than a new URL', function () {
    // Search Console shows the salary queries landing here. The remote,
    // junior, senior and full stack variants are the same search intent with a
    // modifier, so they are answered in named sections instead of separate
    // pages that would compete with this one.
    $body = Blog::where('slug', REACT_SLUG)->value('content');

    foreach ([
        'react-developer-salary-usa',
        'entry-level-react-developer-jobs',
        'senior-react-developer-jobs',
        'remote-react-developer-jobs',
        'frontend-react-developer-jobs',
        'react-full-stack-developer-jobs',
    ] as $anchor) {
        expect($body)->toContain('<h2 id="'.$anchor.'"');
    }
});

it('uses the spelling the top query is typed in', function () {
    // "react js developer salary" is the single largest US query reaching this
    // page, and the page did not contain the string "React JS" at all.
    $body = Blog::where('slug', REACT_SLUG)->value('content');

    expect($body)->toContain('React JS developer')
        ->and(substr_count($body, 'React JS'))->toBeGreaterThanOrEqual(2);
});

it('treats junior and entry level as one posting under two names', function () {
    $body = Blog::where('slug', REACT_SLUG)->value('content');

    expect($body)->toContain('Junior and entry level React developer jobs')
        ->toContain('the same posting under two names');
});

it('separates the two occupations a senior React title can sit in', function () {
    $body = Blog::where('slug', REACT_SLUG)->value('content');

    expect($body)->toContain('Senior React Developer Jobs')
        // The page's argument, carried into the senior section rather than
        // restated: seniority in years is not what sets the benchmark.
        ->toContain('Seniority in years does not decide which; the scope of the job does')
        ->toContain('senior title on the lower band');
});

it('still carries exactly eight People Also Search For entries', function () {
    $body = Blog::where('slug', REACT_SLUG)->value('content');

    $tail = substr($body, strpos($body, 'People Also Search For'));
    $tail = substr($tail, 0, strpos($tail, 'More Job Guides'));

    expect(substr_count($tail, '<h3>'))->toBe(8);
});

it('answers the demand question from the current projection cycle', function () {
    $body = Blog::where('slug', REACT_SLUG)->value('content');

    expect($body)->toContain('Are React Developer Jobs in Demand?')
        ->toContain('10 per cent from 2025 to 2035')
        ->toContain('106,100 openings')
        // The superseded 2024 to 2034 round, named so a reader who meets those
        // numbers elsewhere can date them rather than trust them.
        ->toContain('15.8 per cent rise and 115,200 annual openings')
        ->toContain('superseded');
});

it('names the eligibility filter on federal React work', function () {
    $body = Blog::where('slug', REACT_SLUG)->value('content');

    expect($body)->toContain('React Jobs That Need Clearance or Citizenship')
        // The two OPM axes candidates conflate. A Public Trust designation is
        // an investigation, not a clearance, and the difference sets the start
        // date more than anything on a CV does.
        ->toContain('Public Trust position is a background investigation, not a security clearance')
        ->toContain('Critical-Sensitive')
        ->toContain('integrity and efficiency of the service')
        ->toContain('cannot apply for a clearance by yourself');
});

it('separates the four things a remote React advert can mean', function () {
    $body = Blog::where('slug', REACT_SLUG)->value('content');

    foreach ([
        'Fully remote, US-based',
        'Remote, internationally open',
        'Remote contract',
        'Hybrid-remote',
    ] as $arrangement) {
        expect($body)->toContain($arrangement);
    }
});

it('qualifies the junior and entry level titles without splitting them', function () {
    $body = Blog::where('slug', REACT_SLUG)->value('content');

    expect($body)->toContain('the same posting under two names')
        ->toContain('salaried engineering tracks aimed at recent computer science graduates')
        ->toContain('judge the advert rather than the adjective');
});

it('refuses to answer demand with a vacancy count', function () {
    $body = Blog::where('slug', REACT_SLUG)->value('content');

    expect($body)->toContain('No vacancy count is quoted on this page')
        // Totals lifted from job boards, which the owner's rules bar.
        ->not->toContain('6,764')
        ->not->toContain('129,348')
        ->not->toContain('551 ');
});
