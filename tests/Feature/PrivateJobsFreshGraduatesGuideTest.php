<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\GovernmentJobsPakistanBlogSeeder;
use Database\Seeders\PrivateJobsFreshGraduatesBlogSeeder;

use function Pest\Laravel\get;

const GRAD_SLUG = 'private-jobs-in-pakistan-for-fresh-graduates';

const GRAD_APPLY_URL = 'https://pk.indeed.com/q-fresh-graduate-jobs.html?vjk=db9720f421e1821c';

beforeEach(function () {
    $this->seed(PrivateJobsFreshGraduatesBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', GRAD_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/private-jobs-in-pakistan-for-fresh-graduates.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        // blogs.excerpt is a varchar(255); sqlite accepts a longer one
        // locally and MySQL rejects it on the server.
        ->and(mb_strlen($blog->excerpt))->toBeLessThanOrEqual(255)
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', GRAD_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('names the minimum wage floor beside the advertised bands', function () {
    // The draft quoted PKR 30,000 as a normal graduate starting salary. It is
    // below the notified minimum wage for a full-time role in Punjab, Sindh
    // and KP, so the guide has to say so rather than repeat the number.
    $response = get('/blog/'.GRAD_SLUG)->assertOk();

    $response->assertSee('40,000')
        ->assertSee('40,700')
        ->assertSee('below the legal floor')
        ->assertSee('Labour is a provincial subject');
});

it('softens the claims the draft could not support', function () {
    $body = Blog::where('slug', GRAD_SLUG)->value('content');

    // "many of which run women-only hiring drives" and on-site childcare style
    // benefits could not be sourced for any named employer.
    expect($body)->not->toContain('women-only')
        ->not->toContain('childcare')
        ->toContain('some employers in Lahore and Islamabad provide transport');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', GRAD_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))
        ->not->toContain('Fresh graduate jobs in Lahore');

    get('/blog/'.GRAD_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    expect(app(StructuredDataService::class)->describesSingleVacancy(GRAD_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.GRAD_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one Pakistan listing and does not duplicate it on a re-run', function () {
    $this->seed(PrivateJobsFreshGraduatesBlogSeeder::class);

    $jobs = Job::where('application_url', GRAD_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', GRAD_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('Pakistan')
        // Pay is set by each employer, so no single range would be true.
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('minimum wage');
});

it('links to and from the government jobs guide', function () {
    $this->seed(GovernmentJobsPakistanBlogSeeder::class);

    get('/blog/'.GRAD_SLUG)->assertOk()
        ->assertSee('/blog/government-jobs-in-pakistan', false);

    get('/blog/government-jobs-in-pakistan')->assertOk()
        ->assertSee('/blog/'.GRAD_SLUG, false);
});
