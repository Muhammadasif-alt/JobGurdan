<?php

use App\Models\Blog;
use App\Models\Job;
use App\Services\StructuredDataService;
use Database\Seeders\DigitalMarketingSeoSeeder;
use Database\Seeders\FrontendDeveloperLahoreSeeder;
use Database\Seeders\GovernmentJobsPakistanBlogSeeder;

use function Pest\Laravel\get;

const GOV_SLUG = 'government-jobs-in-pakistan';

const GOV_APPLY_URL = 'https://pk.indeed.com/q-government-l-lahore-jobs.html?vjk=f3eb3a2cbd99869c';

beforeEach(function () {
    $this->seed(GovernmentJobsPakistanBlogSeeder::class);
});

it('publishes the guide with its SEO fields inside the snippet limits', function () {
    $blog = Blog::where('slug', GOV_SLUG)->first();

    expect($blog)->not->toBeNull()
        ->and($blog->status)->toBe('published')
        ->and($blog->featured_image)->toBe('blogs/government-jobs-in-pakistan.jpg')
        ->and($blog->excerpt)->not->toBeEmpty()
        ->and($blog->reading_time)->toBeGreaterThan(3)
        ->and(mb_strlen($blog->meta_title))->toBeLessThanOrEqual(60)
        ->and(mb_strlen($blog->meta_description))->toBeLessThanOrEqual(160);
});

it('ships every image it references', function () {
    $blog = Blog::where('slug', GOV_SLUG)->first();

    preg_match_all('#/public/storage/(blogs/[\w.-]+\.jpg)#', $blog->content, $matches);

    expect($matches[1])->not->toBeEmpty();

    foreach (array_merge([$blog->featured_image], $matches[1]) as $path) {
        expect(file_exists(storage_path('app/public/'.$path)))->toBeTrue("missing image: {$path}");
    }
});

it('separates the BPS civil service from the PPS project scale', function () {
    // The draft had federal posts "advertised through the FPSC for scale PPS-9
    // and above", which contradicts its own FAQ. FPSC recruits BPS seats; PPS
    // posts are hired directly and carry no civil-service pension.
    $response = get('/blog/'.GOV_SLUG)->assertOk();

    $response->assertSee('BPS or PPS')
        ->assertSee('Basic Pay Scale')
        ->assertSee('Project Pay Scale')
        ->assertSee('without the civil-service pension');

    expect($response->getContent())
        ->not->toContain('FPSC for scale PPS');
});

it('drops the invented benefits and the deadline contradiction', function () {
    $body = Blog::where('slug', GOV_SLUG)->value('content');

    // On-site childcare and flexible hours at PSEB and the Planning Ministry
    // could not be sourced anywhere, and the draft promised no extensions in
    // one section while allowing them in another.
    expect($body)->not->toContain('childcare')
        ->not->toContain('flexible working hours')
        ->not->toContain('no extensions')
        ->toContain('Extensions do happen');
});

it('presents the listing table as a dated snapshot rather than live vacancies', function () {
    $body = Blog::where('slug', GOV_SLUG)->value('content');

    expect($body)->toContain('snapshot of roles')
        ->toContain('not as a live vacancy list');
});

it('carries FAQ markup built from the post body', function () {
    $faqs = app(StructuredDataService::class)
        ->faqsFromHtml(Blog::where('slug', GOV_SLUG)->value('content'));

    // "People Also Search For" uses the same h3/p shape and must stay out.
    expect($faqs)->toHaveCount(8)
        ->and(array_column($faqs, 'question'))->not->toContain('PPS scale jobs in Pakistan');

    get('/blog/'.GOV_SLUG)->assertSee('"FAQPage"', false);
});

it('drops JobPosting markup because the apply link is a search page', function () {
    expect(app(StructuredDataService::class)->describesSingleVacancy(GOV_APPLY_URL))->toBeFalse();

    expect(get('/blog/'.GOV_SLUG)->getContent())->not->toContain('"JobPosting"');
});

it('creates one Pakistan listing and does not duplicate it on a re-run', function () {
    $this->seed(GovernmentJobsPakistanBlogSeeder::class);

    $jobs = Job::where('application_url', GOV_APPLY_URL)->get();

    expect($jobs)->toHaveCount(1)
        ->and(Blog::where('slug', GOV_SLUG)->count())->toBe(1)
        ->and($jobs->first()->location->country)->toBe('Pakistan')
        // Pay is set per scale and per advertisement, so no range would be true.
        ->and($jobs->first()->salary_minimum)->toBeNull()
        ->and($jobs->first()->description)->toContain('Basic Pay Scale');
});

it('links to and from the other Pakistan guides', function () {
    $this->seed(FrontendDeveloperLahoreSeeder::class);
    $this->seed(DigitalMarketingSeoSeeder::class);

    get('/blog/'.GOV_SLUG)->assertOk()
        ->assertSee('/blog/senior-frontend-developer-job-at-ers-tech-lahore-react-nextjs-mern', false)
        ->assertSee('/blog/digital-marketing-expert-seo-job-at-urban-solar-remote-pakistan', false);

    foreach ([
        'senior-frontend-developer-job-at-ers-tech-lahore-react-nextjs-mern',
        'digital-marketing-expert-seo-job-at-urban-solar-remote-pakistan',
    ] as $slug) {
        get('/blog/'.$slug)->assertOk()->assertSee('/blog/'.GOV_SLUG, false);
    }
});
